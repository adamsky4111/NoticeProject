<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AccountControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $notice = [
        'firstName' => 'Jan',
        'lastName' => 'Kowalsky',
        'phoneNumber' => '556362111',
    ];
}