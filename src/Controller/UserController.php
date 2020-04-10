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
     * @Route("/add", name="add_user", methods={"POST"})
     * @param Request $request
     * @param SecurityServiceInterface $securityService
     * @return JsonResponse
     */
    public function register(Request $request, SecurityServiceInterface $securityService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = [];

        if ($securityService->usernameExist($data['username'])) {
            array_push($errors, 'usernameTaken');
        }

        if ($securityService->emailExist($data['email'])) {
            array_push($errors, 'emailTaken');
        }

        if ($errors) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_NOT_ACCEPTABLE);
        }
        else {
            $securityService->saveUser($data);

            return new JsonResponse(['status' => 'User created'], Response::HTTP_CREATED);
        }
    }

//    public function api()
//    {
//        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
//    }
}