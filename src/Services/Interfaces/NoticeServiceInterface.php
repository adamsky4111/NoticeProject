<?php

namespace App\Services\Interfaces;

use App\Entity\Notice;

interface NoticeServiceInterface
{

    public function getAll();
    public function saveNotice($data): bool;
    public function updateNotice($id, $data);
    public function getOneById($id);
    public function checkContent($data): bool;
    public function setValues($data, Notice $notice): Notice;
    public function deleteNotice($id);

}