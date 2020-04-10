<?php

namespace App\Controller;

use App\Services\Interfaces\NoticeServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController
{

    private $noticeService;
    public function __construct(NoticeServiceInterface $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @Route("/offers/add", name="add_offer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        dd($data);
        $isOK = ($this->noticeService->saveNotice($data));
        if($isOK){
            return new JsonResponse(['status' => 'Offer created'], Response::HTTP_CREATED);
        }
        else{
            throw new NotFoundHttpException('error');
        }
    }

    /**
     * @Route("/offers/{id}", name="get_one_offer", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $offer = $this->noticeService->getOneById($id);
        $data = $offer->toArray();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/offers", name="get_all_Offers", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $offers = $this->noticeService->getAll();
        $data = [];

        foreach ($offers as $offer) {
            $data[] = $offer->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/offers/{id}", name="update_offer", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $offer = $this->noticeService->updateNotice($id, $data);
        if($offer) {
            return new JsonResponse($offer->toArray(), Response::HTTP_OK);
        }
    }

    /**
     * @Route("/offers/{id}", name="delete_offer", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $this->noticeService->deleteOffer($id);
        return new JsonResponse(['status' => 'Offer deleted'], Response::HTTP_NO_CONTENT);
    }

}