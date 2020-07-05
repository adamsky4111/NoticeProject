<?php

namespace App\Tests\Controller;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class NoticeControllerTest extends AuthenticatedClientWebTestCase
{
    private $url = 'http://localhost/';

    private $notice = [
        'name' => 'dero',
        'description' => 'some informations',
        'city' => 'bar',
        'province' => 'aa',
        'amount' => '40',
        'price' => '599',
    ];

    /**
     * @var NoticeRepositoryInterface
     */
    private $noticeRepository;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->noticeRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Notice::class);
    }

    public function testAddNoticeByActivatedUser()
    {
        $client = clone self::$activatedUser;

        $this->createAddNoticeRequest($client, $this->notice);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice create success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testAddNoticeByActivatedUserWithWrongContentJson()
    {
        $client = clone self::$activatedUser;

        $this->createAddNoticeRequest($client, []);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice create failed'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    private function createAddNoticeRequest($client, $notice)
    {
        $file = tempnam(sys_get_temp_dir(), 'upl');
        imagejpeg(imagecreatetruecolor(10, 10), $file);
        $file = new UploadedFile(
            $file,
            'image.jpeg'
        );
        $files[] = $file;

        $client->request(
            'POST',
            $this->url . 'api/notices/new',
            [],
            $files,
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($notice)
        );
    }

    public function testAddNoticeByNotActivatedUser()
    {
        $client = clone self::$notActivatedUser;

        $this->createAddNoticeRequest($client, $this->notice);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User not activated'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

//    public function testAddNoticeByNotAuthorizatedUser()
//    {
//        $client = clone self::$client;
//
//        $this->createAddNoticeRequest($client);
//
//        $content = json_decode($client->getResponse()->getContent(), true);
//
//        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
//        $this->assertEquals($this->trans('Not authorizated'), $content['message']);
//        $this->assertEquals(false, $content['status']);
//    }

    public function testGetNotices()
    {
        $client = clone self::$client;

        $client->request('GET', $this->url . 'api/notices/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testUpdateNotice()
    {
        $notices = $this->noticeRepository->findAll();
        $notice = $notices[0];
        $client = clone self::$client;

        $client->request(
            'PUT',
            $this->url . 'api/notices/' . $notice->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->notice)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice update success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testUpdateNoticeIfNoContent()
    {
        $notices = $this->noticeRepository->findAll();
        $notice = $notices[0];
        $client = clone self::$client;

        $client->request(
            'PUT',
            $this->url . 'api/notices/' . $notice->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice update failed'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }

    public function testGetOne()
    {
        $notices = $this->noticeRepository->findAll();
        $notice = $notices[0];
        $client = clone self::$client;

        $client->request(
            'GET',
            $this->url . 'api/notices/' . $notice->getId()
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $notices = $this->noticeRepository->findAll();
        $notice = $notices[0];
        $client = clone self::$client;

        $client->request(
            'DELETE',
            $this->url . 'api/notices/' . $notice->getId()
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice delete success'), $content['message']);
        $this->assertEquals(true, $content['status']);
    }

    public function testDeleteWrongId()
    {
        $client = clone self::$client;

        $client->request(
            'DELETE',
            $this->url . 'api/notices/' . -1
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('Notice delete failed'), $content['message']);
        $this->assertEquals(false, $content['status']);
    }
}