<?php

namespace App\Services;

use App\Entity\Notice;
use App\Repository\Interfaces\NoticeRepositoryInterface;
use App\Services\Interfaces\NoticeServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoticeService implements NoticeServiceInterface
{
    private $noticeRepository;

    public function __construct(NoticeRepositoryInterface $noticeRepository)
    {
        $this->noticeRepository = $noticeRepository;
    }

    public function getAll()
    {
        return $this->noticeRepository->findAll();
    }

    public function saveNotice($data): bool
    {
        if($this->checkContent($data)){

            $notice = new Notice();
            $notice->setIsActive(0);

            $this->setValues($data, $notice);
            $this->noticeRepository->save($notice);

            return true;
        }
        return false;
    }

    public function updateNotice($id, $data)
    {
        if($this->checkContent($data)){

            if(!$notice = $this->getOneById($id))
                throw new NotFoundHttpException('error, wrong offer index');
            $this->setValues($data, $notice);
            $this->noticeRepository->save($notice);

            return $notice;
        }
        return false;
    }

    public function getOneById($id)
    {
        return $this->noticeRepository->findOneById($id);
    }

    public function checkContent($data): bool
    {
        if( empty($data['name']) ||
            empty($data['amount']) ||
            empty($data['province']) ||
            empty($data['city']) ||
            empty($data['price']) ||
            empty($data['description']) ||
            empty($data['images'])){
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
        $notice->setImages($data['images']);

        return $notice;
    }

    public function deleteNotice($id)
    {
        if(!$notice = $this->getOneById($id)){
            throw new NotFoundHttpException('error, wrong offer index');
        };
        $this->noticeRepository->deleteNotice($notice);
    }

}