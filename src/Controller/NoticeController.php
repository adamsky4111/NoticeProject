<?php

namespace App\Controller;

use App\Services\Interfaces\NoticeServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController
{

    private $noticeService;

    public function __construct(NoticeServiceInterface $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    /**
     * @Route("/add", name="add_offer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isOK = ($this->noticeService->saveNotice($data, $request->files, '../public/uploads/img'));
        if ($isOK) {
            return new JsonResponse(['status' => true, 'message' => 'Notice created'], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['status' => false, 'message' => 'create failed'], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @Route("/{id}", name="get_one_offer", methods={"GET"})
     */
    public function get($id): JsonResponse
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
        $notice = $this->noticeService->updateNotice($id, $data);
        if ($notice) {
            return new JsonResponse(['status' => true, 'message' => 'Notice Updated'], Response::HTTP_OK);
        } else return new JsonResponse(['status' => false, 'message' => 'update failed'], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Route("/{id}", name="delete_offer", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        if ($this->noticeService->deleteNotice($id)) {
            return new JsonResponse(['status' => true, 'message' => 'Notice Deleted'], Response::HTTP_OK);
        } else return new JsonResponse(['status' => false, 'message' => 'delete failed'], Response::HTTP_NOT_ACCEPTABLE);
    }

}