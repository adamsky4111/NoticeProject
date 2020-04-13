<?php

namespace App\Tests;

use App\Entity\Notice;
use App\Entity\User;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;


class UserControllerTest extends WebTestCase
{
    private $url = 'http://localhost/';

    private $user = [
        'username' => 'foo',
        'email' => 'foo@wp.get',
        'password' => 'password',
    ];

    private $userForEmailValidate = [
        'username' => 'boo',
        'email' => 'foo@wp.get',
        'password' => 'password',
    ];

    private $userForUsernameValidate = [
        'username' => 'foo',
        'email' => 'foo@wp.get2',
        'password' => 'password',
    ];

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

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

        $this->userRepository = $this
            ->entityManager
            ->getRepository(User::class);
    }

    public function testRegister()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . 'api/users/new',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->user)
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testIfUsernameAlreadyExist()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . 'api/users/new',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->userForEmailValidate)
        );

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
    }

    public function testIfEmailAlreadyExist()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url . 'api/users/new',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($this->userForUsernameValidate)
        );

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse()->getStatusCode());
    }

}