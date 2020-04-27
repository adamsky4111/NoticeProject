<?php

namespace App\Tests\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class AccountControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $route = 'api/account/';

    private $account = [
        'firstName' => 'juzek',
        'lastName' => 'juzek2',
        'province' => ' tak',
        'city' => 'tak',
        'phone' => '44444444444',
    ];

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager');

        $this->userRepository = $this
            ->entityManager
            ->getRepository(User::class);


    }

    public function testEditAccount()
    {
        $this->createUser();
        $client = $this->createAuthenticatedClient();

        $client->request(
            'PUT',
            $this->url . $this->route . 'edit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->account)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    private function createUser()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $user = new User();
        $user->setEmail('email@email.com');
        $user->setUsername('username');
        $user->setPassword($encoder->encodePassword($user, 'password'));
        $user->setIsActive(true);
        $user->setAccount(new Account());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function createAuthenticatedClient($username = 'username', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
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

        return $client;
    }


}