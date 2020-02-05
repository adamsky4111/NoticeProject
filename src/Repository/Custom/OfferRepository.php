<?php


namespace App\Repository\Custom;

use App\Entity\Offer;
use App\Repository\Interfaces\OfferRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class OfferRepository implements OfferRepositoryInterface
{
    private $repository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Offer::class);
    }

    public function save($offer)
    {
        $this->entityManager->persist($offer);
        $this->entityManager->flush();
    }

    public function  findAll()
    {
        return $this->repository->findAll();
    }

    public function findOneById($id)
    {
        $this->repository->findOneBy([
            "id"=>$id,
        ]);
    }
}
