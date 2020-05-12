<?php

namespace App\Tests\Controller;

use App\DataFixtures\NoticeControllerTestFixtures\ManyActivatedNoticesWithOneUser;
use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use App\Tests\AuthenticatedClientWebTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        $this->noticeRepository = self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Notice::class);
    }

    public function testAddNotice()
    {
        $client = clone self::$activatedUser;

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
            json_encode($this->notice)
        );
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testGetNotices()
    {
        $client = clone self::$client;

        $client->request('GET', $this->url . 'api/notices/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testPut()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = clone self::$client;

            $client->request(
                'PUT',
                $this->url . 'api/notices/' . $notice->getId(),
                [],
                [],
                array('CONTENT_TYPE' => 'application/json'),
                json_encode($this->notice)
            );

            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('Database is empty');
        }
    }

    public function testGetOne()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = clone self::$client;

            $client->request(
                'GET',
                $this->url . 'api/notices/' . $notice->getId()
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('Database is empty');
        }
    }

    public function testDelete()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = clone self::$client;

            $client->request(
                'DELETE',
                $this->url . 'api/notices/' . $notice->getId()
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        } else {
            throw new Exception('Database is empty');
        }
    }
}