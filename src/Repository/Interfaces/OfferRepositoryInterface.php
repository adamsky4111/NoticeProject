<?php

namespace App\Repository\Interfaces;

interface OfferRepositoryInterface
{
    public function save($offer);
    public function findAll();
    public function findOneById($id);
    public function deleteOffer($offer);
}