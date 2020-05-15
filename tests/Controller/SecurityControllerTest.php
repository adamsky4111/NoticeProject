<?php


namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\Response;


class SecurityControllerTest extends AuthenticatedClientWebTestCase
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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->userRepository = self::$kernel
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class);
    }

    public function testRegister()
    {
        $client = clone self::$client;

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->user)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User create success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testIfUsernameAlreadyExist()
    {
        $client = clone self::$client;

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->userForUsernameValidate)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Username taken'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    public function testIfEmailAlreadyExist()
    {
        $client = clone self::$client;

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->userForEmailValidate)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Email taken'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    public function testIfUsernameAndEmailAlreadyExist()
    {
        $client = clone self::$client;

        $client->request(
            'POST',
            $this->url . $this->route . 'register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->user)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Username and Email taken'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

//    public function testChangePassword()
//    {
//        if ($users = $this->userRepository->findAll()) {
//            $user = $users[0];
//            $client = static::createClient();
//
//            $client->request(
//                'PUT',
//                $this->url . $this->route . 'change-password/' . $user->getUsername(),
//                [],
//                [],
//                ['CONTENT_TYPE' => 'application/json'],
//                json_encode([
//                    'newPassword' => 'newPassword',
//                ])
//            );
//            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//        } else {
//            throw new Exception('no users in database.');
//        }
//    }

    public function testLogin()
    {
        $client = clone self::$client;

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

    private function createForgotPasswordRequest($client, $email)
    {
        $client->request(
            'POST',
            $this->url . $this->route . 'forgot-password',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
            ])
        );
    }
    public function testForgotPasswordIfEmailExist()
    {
        $client = clone self::$client;

        $this->createForgotPasswordRequest($client, 'foo@wp.get');

        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Reset password success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testForgotPasswordIfEmailDoesntExist()
    {
        $client = clone self::$client;

        $this->createForgotPasswordRequest($client, 'thereIsNoEmailLikeThis@no');

        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Reset password failed'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }
}