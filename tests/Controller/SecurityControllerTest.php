<?php


namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;


class SecurityControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $route = 'api/security/';

    private $user = [
        'username' => 'foo',
        'email' => 'foo@wp.get',
        'password' => 'password',
    ];

    private $userForEmailValidate = [
        'username' => 'boo',
        'email' => 'foo@wp.get',
        'password' => 'password',
    ];

    private $userForUsernameValidate = [
        'username' => 'foo',
        'email' => 'foo@wp.get2',
        'password' => 'password',
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

    public function testRegister()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->user)
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testIfUsernameAlreadyExist()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->userForEmailValidate)
        );

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
    }

    public function testIfEmailAlreadyExist()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->userForUsernameValidate)
        );

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
    }

    public function testChangePassword()
    {
        if ($users = $this->userRepository->findAll()) {
            $user = $users[0];
            $client = static::createClient();

            $client->request(
                'PUT',
                $this->url . $this->route . 'change-password/' . $user->getUsername(),
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode([
                    'newPassword' => 'newPassword',
                ])
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('no users in database.');
        }
    }

    public function testLogin()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            $this->url . 'api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'foo',
                'password' => 'password',
            ])
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testForgotPassword()
    {
        if ($users = $this->userRepository->findAll()) {
            $user = $users[0];
            $client = static::createClient();
            $client->request(
                'POST',
                $this->url . $this->route . 'forgot-password',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode([
                    'email' => 'foo@wp.get',
                ])
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('no users in database.');
        }

    }
}