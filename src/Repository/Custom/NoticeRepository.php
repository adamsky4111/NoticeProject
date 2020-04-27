<?php

namespace App\Repository\Custom;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class NoticeRepository implements NoticeRepositoryInterface
{
    private $repository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Notice::class);
    }

    public function save($notice)
    {
        $this->entityManager->persist($notice);
        $this->entityManager->flush();
    }

    public function  findAll()
    {
        return $this->repository->findAll();
    }

    public function findOneById($id)
    {
        return $this->repository->findOneBy(["id" => $id]);
    }

    public function deleteNotice($notice)
    {
        $this->entityManager->remove($notice);
        $this->entityManager->flush();
    }
}
