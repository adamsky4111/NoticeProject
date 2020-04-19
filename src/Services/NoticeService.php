<?php

namespace App\Services;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use App\Services\Interfaces\NoticeServiceInterface;
use App\Services\Interfaces\UploadFilesServiceInterface;

class NoticeService implements NoticeServiceInterface
{
    private $noticeRepository;

    private $uploadFilesService;

    public function __construct(UploadFilesServiceInterface $uploadFilesService,
                                NoticeRepositoryInterface $noticeRepository)
    {
        $this->noticeRepository = $noticeRepository;
        $this->uploadFilesService = $uploadFilesService;
    }

    public function getAll()
    {
        return $this->noticeRepository->findAll();
    }

    public function saveNotice($data, $files, $imgDirectory): bool
    {
        if ($this->checkContent($data)) {

            $notice = new Notice();
            $notice->setIsActive(0);
            $images = $this->uploadFilesService->uploadFiles($files, $imgDirectory);
            $this->setValues($data, $notice);
            $notice->setImages($images);
            $this->noticeRepository->save($notice);

            return true;
        }
        return false;
    }

    public function updateNotice($id, $data): bool
    {
        if ($this->checkContent($data)) {
            if (!$notice = $this->getOneById($id))
                return false;
            $this->setValues($data, $notice);
            $this->noticeRepository->save($notice);

            return true;
        }
        return false;
    }

    public function getOneById($id)
    {
        return $this->noticeRepository->findOneById($id);
    }

    public function checkContent($data): bool
    {
        if (empty($data['name']) ||
            empty($data['amount']) ||
            empty($data['province']) ||
            empty($data['city']) ||
            empty($data['price']) ||
            empty($data['description'])) {
            return false;
        }

        return true;
    }

    public function setValues($data, Notice $notice): Notice
    {
        $notice->setName($data['name']);
        $notice->setAmount($data['amount']);
        $notice->setProvince($data['province']);
        $notice->setCity($data['city']);
        $notice->setPrice($data['price']);
        $notice->setDescription($data['description']);

        return $notice;
    }

    public function deleteNotice($id)
    {
        if (!$notice = $this->getOneById($id)) {
            return false;
        };
        $this->noticeRepository->deleteNotice($notice);

        return true;
    }

}