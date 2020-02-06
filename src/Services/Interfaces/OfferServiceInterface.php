<?php

namespace App\Services\Interfaces;

use App\Entity\Offer;

interface OfferServiceInterface
{

    public function getAll();
    public function saveOffer($data): bool;
    public function updateOffer($id, $data);
    public function getOneById($id);
    public function checkContent($data): bool;
    public function setValues($data, Offer $offer): Offer;
    public function deleteOffer($id);

}