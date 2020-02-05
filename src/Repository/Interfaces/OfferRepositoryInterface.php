<?php

namespace App\Repository\Interfaces;

interface OfferRepositoryInterface
{
    public function save($offer);
    public function findAll();
}