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

    public function testActivatedUserEditAccount()
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

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User account update success'), $this->trans($content['message']));
        $this->assertEquals(true, $content['status']);
    }

    public function testNotActivatedUserEditAccount()
    {
        $client = clone self::$notActivatedUser;

        $client->request(
            'PUT',
            $this->route . 'edit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->account)
        );

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->trans('User not activated'), $this->trans($content['message']));
        $this->assertEquals(false, $content['status']);
    }
}