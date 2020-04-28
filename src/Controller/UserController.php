<?php

namespace App\Controller;

use App\Services\Interfaces\SecurityServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     * @var SecurityServiceInterface
     */
    private $userService;

    private $translator;

    public function __construct(UserServiceInterface $userService,
                                TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="get_all_users", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        $users = $this->userService->getUsers();
        $data = [];

        foreach ($users as $user) {
            $data[] = $user->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{username}", name="get_one_user", methods={"GET"})
     * @param $username
     * @return JsonResponse
     */
    public function getOne($username): JsonResponse
    {
        $user = $this->userService->getUserByUsername($username);

        if ($user) {
            $data = $user->toArray();
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return $this->createResponse(
                false,
                'User wrong username',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @Route("/{username}", name="delete_user", methods={"DELETE"})
     * @param $username
     * @return JsonResponse
     */
    public function delete($username): JsonResponse
    {
        $isOK = $this->userService->deleteUser($username);

        if ($isOK) {
            return $this->createResponse(
                true,
                'User delete success'
                , Response::HTTP_OK);
        } else {
            return $this->createResponse(
                false,
                'User delete failed',
                Response::HTTP_NOT_ACCEPTABLE);
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