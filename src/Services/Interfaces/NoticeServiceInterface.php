<?php

namespace App\Services\Interfaces;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use Symfony\Component\Security\Core\Security;

interface NoticeServiceInterface
{
    public function __construct(UploadFilesServiceInterface $uploadFilesService,
                                NoticeRepositoryInterface $noticeRepository,
                                Security $security);

    public function getAll();

    public function saveNotice($data, $files, $imgDirectory): bool;

    public function updateNotice($id, $data);

    public function getOneById($id);

    public function checkContent($data): bool;

    public function setValues($data, Notice $notice): Notice;

    public function deleteNotice($id);

    public function getAllActive();
}