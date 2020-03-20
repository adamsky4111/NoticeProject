<?php

namespace App\Controller;

use App\Services\Interfaces\OfferServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OfferController
{

    private $offerService;
    public function __construct(OfferServiceInterface $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @Route("/offers/add", name="add_offer", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isOK = ($this->offerService->saveOffer($data));
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
        $offer = $this->offerService->getOneById($id);
        $data = $offer->toArray();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/offers", name="get_all_Offers", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $offers = $this->offerService->getAll();
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
        $offer = $this->offerService->updateOffer($id, $data);
        if($offer) {
            return new JsonResponse($offer->toArray(), Response::HTTP_OK);
        }
    }

    /**
     * @Route("/offers/{id}", name="delete_offer", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $this->offerService->deleteOffer($id);
        return new JsonResponse(['status' => 'Offer deleted'], Response::HTTP_NO_CONTENT);
    }

}