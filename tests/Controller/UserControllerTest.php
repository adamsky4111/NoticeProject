<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserControllerTest extends AuthenticatedClientWebTestCase
{
    private $url = 'http://localhost/';

    private $route = 'api/users/';

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

        $this->userRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class);
    }

    public function testGetOne()
    {
        $users = $this->userRepository->findAll();
        $user = $users[0];

        $client = clone self::$client;

        $client->request(
            'GET',
            $this->url . $this->route . $user->getUsername()
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testGetAllUsers()
    {
        $client = clone self::$client;

        $client->request(
            'GET',
            $this->url . $this->route
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $users = $this->userRepository->findAll();
        $user = $users[0];
        $client = clone self::$client;

        $client->request(
            'DELETE',
            $this->url . $this->route . $user->getUsername()
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User delete success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testDeleteIfWrongUsername()
    {
        $client = clone self::$client;

        $client->request(
            'DELETE',
            $this->url . $this->route . 'thereIsNoUsernameLikeThis'
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User delete failed'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }
}