<?php

namespace App\Tests\Controller;

use App\Tests\AuthenticatedClientWebTestCase;
use Symfony\Component\HttpFoundation\Response;


class AccountControllerTest extends AuthenticatedClientWebTestCase
{

    private $route = '/api/account/';

    private $account = [
        'firstName' => 'juzek',
        'lastName' => 'juzek2',
        'province' => ' tak',
        'city' => 'tak',
        'phone' => '44444444444',
    ];

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function testEditAccount()
    {
        $client = clone self::$activatedUser;

        $client->request(
            'PUT',
            $this->route . 'edit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->account)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}