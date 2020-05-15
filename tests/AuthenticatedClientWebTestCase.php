<?php

namespace App\Tests;

use App\DataFixtures\OneActivatedUserWithoutNotices;
use App\DataFixtures\UsersForAuthorize;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AuthenticatedClientWebTestCase
 * @package App\Tests
 * class contains activated user and not activated, for specific test case.
 */
abstract class AuthenticatedClientWebTestCase extends AbstractFixtureWebTestCase
{
    protected static $activatedUser;

    protected static $notActivatedUser;

    protected static $client;

    protected function setUp()
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

        if (null === self::$activatedUser) {
            self::$activatedUser = clone self::$client;
            $this->authorizeClient(self::$activatedUser);
        }

        if (null === self::$notActivatedUser) {
            self::$notActivatedUser = clone self::$client;
            $this->authorizeClient(self::$notActivatedUser, 'username2');
        }

        parent::setUp();
    }

    protected function getPasswordEncoder()
    {
        /**
         * @var UserPasswordEncoderInterface $encoder
         */
        $encoder = self::$kernel->getContainer()->get('security.password_encoder');
        return $encoder;
    }

    protected function authorizeClient(KernelBrowser $client, $username = 'username', $password = 'password')
    {
        $encoder = $this->getPasswordEncoder();
        $this->addFixture(new UsersForAuthorize($encoder));
        $this->executeFixtures();

        $client->request(
            'POST',
            'http://localhost/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }
}