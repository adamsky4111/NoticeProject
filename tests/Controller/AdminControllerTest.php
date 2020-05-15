<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\Doctrine\UserRepository;
use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends AuthenticatedClientWebTestCase
{

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    public function setUp()
    {
        parent::setUp();
        $this->userRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(User::class);
    }

    public function testBanUser()
    {
        $users = $this->userRepository->findAll();
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/ban/' . $users[0]->getId()
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User is banned'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testBanIfUserDoesntExist()
    {
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/ban/' . -1
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User not found'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    public function testUnbanUser()
    {
        $users = $this->userRepository->findAll();
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/unban/' . $users[0]->getId()
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User is unbanned'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testUnbanIfUserDoesntExist()
    {
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/unban/' . -1
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User not found'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }
}