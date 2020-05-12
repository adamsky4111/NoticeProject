<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Tests\AuthenticatedClientWebTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
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

        $this->userRepository = self::$kernel
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class);
    }

    public function testGetOne()
    {
        if ($users = $this->userRepository->findAll()) {
            $user = $users[0];
            $client = self::$client;

            $client->request(
                'GET',
                $this->url . $this->route . $user->getUsername()
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('no users in database.');
        }
    }

    public function testGetAllUsers()
    {
        $client = self::$client;

        $client->request(
            'GET',
            $this->url . $this->route
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        if ($users = $this->userRepository->findAll()) {
            $user = $users[0];
            $client = self::$client;

            $client->request(
                'DELETE',
                $this->url . $this->route . $user->getUsername()
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('no users in database.');
        }
    }
}