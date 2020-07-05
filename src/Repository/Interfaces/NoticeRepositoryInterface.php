<?php

namespace App\Repository\Interfaces;

interface NoticeRepositoryInterface
{
    public function save($notice);

    public function findAll();

    public function findAllActive();

    public function findOneById($id);

    public function deleteNotice($notice);
}