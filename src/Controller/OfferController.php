<?php


namespace App\Controller;


use App\Services\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offers")
 */

class OfferController extends AbstractController
{
    private $offerService;
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @Route("/add", name="add_offer", methods={"POST"})
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


}