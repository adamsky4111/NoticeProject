<?php

namespace App\Controller;

use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, SecurityServiceInterface $securityService)
    {
        $securityService->saveUser($request);
        return new JsonResponse(['status' => 'User created'], Response::HTTP_CREATED);
    }

//    public function api()
//    {
//        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
//    }
}