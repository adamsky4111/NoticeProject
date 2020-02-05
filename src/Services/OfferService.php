<?php

namespace App\Services;

use App\Entity\Offer;
use App\Repository\Interfaces\OfferRepositoryInterface;

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
        $name = $data['name'];
        $size = $data['size'];
        $area = $data['area'];
        $price = $data['price'];
        $description = $data['description'];
        $image = $data['image'];
        if( empty($name) ||
            empty($size) ||
            empty($area) ||
            empty($price) ||
            empty($description) ||
            empty($image))
            return false;
        else
        {
            $offer = new Offer();
            $offer->setIsActive(0);
            $offer->setName($name);
            $offer->setSize($size);
            $offer->setArea($area);
            $offer->setPrice($price);
            $offer->setDescription($description);
            $offer->setImage($image);
            $this->offerRepository->save($offer);
            return true;
        }

    }

    public function getOneById($id)
    {
        return $this->offerRepository->findOneById($id);
    }
}