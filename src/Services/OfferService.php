<?php

namespace App\Services;

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
}