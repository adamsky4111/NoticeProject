<?php

namespace App\Tests;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;


class AccountControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $notice = [
        'firstName' => 'Jan',
        'lastName' => 'Kowalsky',
        'phoneNumber' => '556362111',
    ];
}