<?php

namespace App\Services;

use App\Entity\Offer;
use App\Repository\Interfaces\OfferRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OfferService
{
    private $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getAll()
    {
        return $this->offerRepository->findAll();
    }

    public function saveOffer($data): bool
    {
        if($this->checkContent($data)){

            $offer = new Offer();
            $offer->setIsActive(0);

            $this->setValues($data, $offer);
            $this->offerRepository->save($offer);

            return true;
        }
        return false;
    }

    public function updateOffer($id, $data)
    {
        if($this->checkContent($data)){

            if(!$offer = $this->getOneById($id))
                throw new NotFoundHttpException('error, wrong offer index');
            $this->setValues($data, $offer);
            $this->offerRepository->save($offer);

            return $offer;
        }
        return false;
    }

    public function getOneById($id)
    {
        return $this->offerRepository->findOneById($id);
    }

    public function checkContent($data): bool
    {
        if( empty($data['name']) ||
            empty($data['size']) ||
            empty($data['area']) ||
            empty($data['price']) ||
            empty($data['description']) ||
            empty($data['image'])){
            return false;
        }

        return true;
    }

    public function setValues($data, Offer $offer): Offer
    {
        $offer->setName($data['name']);
        $offer->setSize($data['size']);
        $offer->setArea($data['area']);
        $offer->setPrice($data['price']);
        $offer->setDescription($data['description']);
        $offer->setImage($data['image']);

        return $offer;
    }

    public function deleteOffer($id)
    {
        if(!$offer = $this->getOneById($id)){
            throw new NotFoundHttpException('error, wrong offer index');
        };
        $this->offerRepository->deleteOffer($offer);
    }

}