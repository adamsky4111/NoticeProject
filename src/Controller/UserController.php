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
     * @Route("/new", name="new_user", methods={"POST"})
     * @param Request $request
     * @param SecurityServiceInterface $securityService
     * @return JsonResponse
     */
    public function register(Request $request, SecurityServiceInterface $securityService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];

        if ($securityService->usernameExist($data['username'])) {
            $errors[] = 'username taken';
        }

        if ($securityService->emailExist($data['email'])) {
            $errors[] = 'email taken';
        }

        if ($errors) {
            return new JsonResponse(['status' => false, 'message' => $errors], Response::HTTP_NOT_ACCEPTABLE);
        } else {
            $securityService->saveUser($data);

            return new JsonResponse(['status' => true, 'message' => 'User created'], Response::HTTP_CREATED);
        }
    }

//    public function api()
//    {
//        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
//    }
}