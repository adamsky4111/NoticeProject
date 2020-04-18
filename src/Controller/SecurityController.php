<?php

namespace App\Controller;

use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @var SecurityServiceInterface
     */
    private $securityService;

    private $translator;

    public function __construct(SecurityServiceInterface $securityService,
                                TranslatorInterface $translator)
    {
        $this->securityService = $securityService;
        $this->translator = $translator;
    }

    /**
     * @Route("/register", name="register_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];

        if ($this->securityService->usernameExist($data['username'])) {
            $errors[] = 'Username';
        }

        if ($this->securityService->emailExist($data['email'])) {
            $errors[] = 'Email';
        }

        if ($errors) {
            $stringErrors = implode($errors, ' and '); // create "Username and Email taken" translation
            $stringErrors .= ' taken';

            return $this->createResponse(
                false,
                $stringErrors,
                Response::HTTP_NOT_ACCEPTABLE
            );
        } else {
            $this->securityService->saveUser($data);

            return $this->createResponse(
                true,
                'User create success',
                Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/activate", name="activate_user", methods={"GET"})
     * @param Request $request
     */
    public function activate(Request $request)
    {
        $userId = $request->query->get('user');
        $activationCode = $request->query->get('code');
        $user = $this->securityService->getUserById($userId);
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

        $isOK = $this->securityService->changePassword($username, $data['newPassword']);
        if ($isOK) {
            return $this->createResponse(
                true,
                'User password change success',
                Response::HTTP_OK);
        } else {
            return $this->createResponse(
                false,
                'User password change failed',
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