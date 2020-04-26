<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;


class UserControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $route = 'api/users/';

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

    public function testGetOne()
    {
        if ($users = $this->userRepository->findAll()) {
            $user = $users[0];
            $client = static::createClient();

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
        $client = static::createClient();
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
            $client = static::createClient();

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