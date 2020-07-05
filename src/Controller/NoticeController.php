<?php

namespace App\Controller;

use App\Services\Interfaces\NoticeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NoticeController extends AbstractController
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
     * @Route("/new", name="new_notice", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        if ($this->getUser()->getAccount() === null) {
            return $this->createResponse(
                false,
                'User not activated',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

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
     * @Route("/{id}", name="get_one_notice", methods={"GET"})
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
     * @Route("/active", name="get_all_active_notices", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $notices = $this->noticeService->getAllActive();
        $data = [];

        foreach ($notices as $notice) {
            $data[] = $notice->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/", name="get_all_notices", methods={"GET"})
     */
    public function getNotices()
    {
        $notices = $this->noticeService->getAll();
        $data = [];

        foreach ($notices as $notice) {
            $data[] = $notice->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_notice", methods={"PUT"})
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
     * @Route("/{id}", name="delete_notice", methods={"DELETE"})
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
                Response::HTTP_NOT_FOUND
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