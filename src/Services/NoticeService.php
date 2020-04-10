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

            if(!$offer = $this->getOneById($id))
                throw new NotFoundHttpException('error, wrong offer index');
            $this->setValues($data, $offer);
            $this->noticeRepository->save($offer);

            return $offer;
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
            empty($data['image'])){
            return false;
        }

        return true;
    }

    public function setValues($data, Notice $notice): Notice
    {
        $offer->setName($data['name']);
        $offer->setAmount($data['size']);
        $offer->setProvince($data['province']);
        $offer->setCity($data['city']);
        $offer->setPrice($data['price']);
        $offer->setDescription($data['description']);
        $offer->setImage($data['image']);

        return $offer;
    }

    public function deleteNotice($id)
    {
        if(!$offer = $this->getOneById($id)){
            throw new NotFoundHttpException('error, wrong offer index');
        };
        $this->noticeRepository->deleteNotice($offer);
    }

}