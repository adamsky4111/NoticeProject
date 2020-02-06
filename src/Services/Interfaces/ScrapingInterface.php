<?php

namespace App\Services;

interface ScrapingInterface
{
    public function scrap();
    public function save($offers);
}