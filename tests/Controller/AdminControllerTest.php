<?php

namespace App\Tests\Controller;

use App\DataFixtures\UsersForAuthorize;
use App\DataFixtures\UserWithNoActivatedNotices;
use App\Entity\Notice;
use App\Entity\User;
use App\Repository\Doctrine\NoticeRepository;
use App\Repository\Doctrine\UserRepository;
use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends AuthenticatedClientWebTestCase
{

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * @var NoticeRepository $noticeRepository
     */
    private $noticeRepository;

    public function setUp()
    {
        parent::setUp();
        $this->addFixture(new UserWithNoActivatedNotices($this->getPasswordEncoder()));
        $this->addFixture(new UsersForAuthorize($this->getPasswordEncoder()));
        $this->executeFixtures();
        $entityManager = self::$kernel->getContainer()
            ->get('doctrine.orm.entity_manager');

        $this->userRepository = $entityManager->getRepository(User::class);

        $this->noticeRepository = $entityManager->getRepository(Notice::class);
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

    public function testActivateNotice()
    {
        $notices = $this->noticeRepository->findAll();
        $notice = $notices[0];

        if ($notice->getIsActive() === true) {
            throw new \Exception('There is no not activated notice for test');
        }

        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/notice/activate/' . $notice->getId()
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice is activated'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testActivateNotExistingNotice()
    {
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/notice/activate/' . -1
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice not found'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    public function testActivateNotExistingUser()
    {
        $client = clone self::$client;

        $client->request(
            'PUT',
            '/api/admin/user/activate/' . -1
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User not found'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }
}