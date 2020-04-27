<?php

namespace App\Controller;

use App\Services\Interfaces\AccountManagerInterface;
use App\Services\Interfaces\AdminServiceInterface;
use App\Services\Interfaces\NoticeServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/admin")
 */
class AdminController extends AbstractController
{
    private $adminService;

    private $noticeService;

    private $userService;

    private $translator;

    public function __construct(AdminServiceInterface $adminService,
                                NoticeServiceInterface $noticeService,
                                UserServiceInterface $userService,
                                TranslatorInterface $translator)
    {
        $this->adminService = $adminService;
        $this->noticeService = $noticeService;
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * @Route("/ban/{id}", name="ban_user", methods={"PUT"})
     * @param $id
     * @return JsonResponse
     */
    public function banUser($id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if ($user === null) {
            return $this->createResponse(
                false,
                'User not found',
                Response::HTTP_NOT_FOUND
            );
        }

        $this->adminService->ban($user);

        return $this->createResponse(
            true,
            'User is banned',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/unban/{id}", name="unban_user", methods={"PUT"})
     * @param $id
     * @return JsonResponse
     */
    public function unbanUser($id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if ($user === null) {
            return $this->createResponse(
                false,
                'User not found',
                Response::HTTP_NOT_FOUND
            );
        }

        $this->adminService->unBan($user);

        return $this->createResponse(
            true,
            'User is unbanned',
            Response::HTTP_OK
        );
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
