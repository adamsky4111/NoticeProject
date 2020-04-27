<?php

namespace App\Tests\Controller;

use App\Entity\Account;
use App\Entity\Notice;
use App\Entity\User;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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

    public function testAddNotice()
    {
        $this->createUser();

        $client = $this->createAuthenticatedClient();
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

    private function createUser()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $user = new User();
        $user->setEmail('email@email.com');
        $user->setUsername('username');
        $user->setPassword($encoder->encodePassword($user, 'password'));
        $user->setIsActive(true);
        $user->setAccount(new Account());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function createAuthenticatedClient($username = 'username', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testGetNotices()
    {
        $client = static::createClient();
        $client->request('GET', $this->url . 'api/notices/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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
        } else {
            throw new Exception('Database is empty');
        }
    }

    public function testGetOne()
    {
        if ($notices = $this->noticeRepository->findAll()) {
            $notice = $notices[0];
            $client = static::createClient();

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
            $client = static::createClient();

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