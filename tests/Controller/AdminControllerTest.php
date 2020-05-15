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
}