<?php

namespace App\Controller;

use App\Services\Interfaces\NoticeServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NoticeController
{

    private $noticeService;

    private $translator;

    public function __construct(NoticeServiceInterface $noticeService,
                                TranslatorInterface $translator)
    {
        $this->noticeService = $noticeService;
        $this->translator = $translator;
    }

    /**
     * @Route("/new", name="new_offer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isOK = $this->noticeService->saveNotice(
            $data,
            $request->files,
            '../public/uploads/img'
        );
        if ($isOK) {
            return $this->createResponse(
                true,
                'Notice create success',
                Response::HTTP_CREATED
            );
        } else {
            return $this->createResponse(
                false,
                'Notice create failed',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    /**
     * @Route("/{id}", name="get_one_offer", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOne($id): JsonResponse
    {
        $notice = $this->noticeService->getOneById($id);
        $data = $notice->toArray();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", name="get_all_Offers", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $notices = $this->noticeService->getAll();
        $data = [];

        foreach ($notices as $notice) {
            $data[] = $notice->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_offer", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isOK = $this->noticeService->updateNotice($id, $data);
        if ($isOK) {
            return $this->createResponse(
                true,
                'Notice update success',
                Response::HTTP_OK
            );
        } else {
            return $this->createResponse(
                false,
                'Notice update failed',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_offer", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $isOK = $this->noticeService->deleteNotice($id);
        if ($isOK) {
            return $this->createResponse(
                true,
                'Notice delete success',
                Response::HTTP_OK
            );
        } else {
            return $this->createResponse(
                false,
                'Notice delete failed',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    public function createResponse($status, $message, $code)
    {
        return new JsonResponse([
            'status' => $status,
            'message' => $this->translator->trans($message)
        ], $code
        );
    }

}