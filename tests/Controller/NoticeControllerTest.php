<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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

    public function testGetNotices()
    {
        $client = static::createClient();
        $client->request('GET', $this->url . 'api/notices/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
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

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}