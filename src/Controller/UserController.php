<?php

namespace App\Controller;

use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     * @var SecurityServiceInterface
     */
    private $securityService;

    public function __construct(SecurityServiceInterface $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * @Route("/new", name="new_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];

        if ($this->securityService->usernameExist($data['username'])) {
            $errors[] = 'username taken';
        }

        if ($this->securityService->emailExist($data['email'])) {
            $errors[] = 'email taken';
        }

        if ($errors) {
            return new JsonResponse([
                'status' => false,
                'message' => $errors
            ], Response::HTTP_NOT_ACCEPTABLE);
        } else {
            $this->securityService->saveUser($data);

            return new JsonResponse([
                'status' => true,
                'message' => 'User created'
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/", name="get_all_users", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        $users = $this->securityService->getUsers();
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
        $user = $this->securityService->getUserByUsername($username);
        $data = $user->toArray();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{username}", name="delete_user", methods={"DELETE"})
     * @param $username
     * @return JsonResponse
     */
    public function delete($username): JsonResponse
    {
        if ($this->securityService->deleteUser($username)) {
            return new JsonResponse([
                'status' => true,
                'message' => 'Notice Deleted'
            ], Response::HTTP_OK);
        } else return new JsonResponse([
            'status' => false,
            'message' => 'delete failed'
        ], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Route("/forgot-password/{username}", name="forgot_password", methods={"POST"})
     * @return JsonResponse
     */
    public function forgotPassword()
    {
        return new JsonResponse('TODO');
    }

    /**
     * @Route("/change-password/{username}", name="change_password", methods={"PUT"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function changePassword(Request $request, $username)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->securityService->changePassword($username, $data['newPassword'])) {
            return new JsonResponse([
                'status' => true,
                'message' => 'password changed'
            ], Response::HTTP_OK);
        } else return new JsonResponse([
            'status' => false,
            'message' => 'could not change password for this user.'
        ]);
    }
}