<?php

namespace App\Tests;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;


class NoticeControllerTest extends WebTestCase
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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var NoticeRepositoryInterface
     */
    private $noticeRepository;

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

        $this->noticeRepository = $this
            ->entityManager
            ->getRepository(Notice::class);
    }

    public function testGetNotices()
    {
        $client = static::createClient();
        $client->request('GET', $this->url . 'api/notices/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testAddNotice()
    {
        $client = static::createClient();
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
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->notice)
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testGetOneNotice()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = static::createClient();

            $client->request(
                'DELETE',
                $this->url . 'api/notices/' . $notice->getId()
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
        else {
            throw new Exception('Database is empty');
        }
    }

    public function testPut()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = static::createClient();

            $client->request(
                'PUT',
                $this->url . 'api/notices/' . $notice->getId(),
                [],
                [],
                array('CONTENT_TYPE' => 'application/json'),
                json_encode($this->notice)
            );
            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
        else {
            throw new Exception('Database is empty');
        }
    }
    
}