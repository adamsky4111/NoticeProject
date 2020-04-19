<?php

namespace App\Controller;

use App\Services\Interfaces\AccountActivationInterface;
use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @Route("/api/security/register", name="register_user", methods={"POST"})
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
            $stringErrors = implode(
                $errors,
                ' and '
            ); // create "Username and Email taken" translation
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
     * @param AccountActivationInterface $accountActivation
     */
    public function activate(Request $request,
                             AccountActivationInterface $accountActivation)
    {
        $userId = $request->query->get('user');
        $activationCode = $request->query->get('code');
        if ($userId && $activationCode) {
            return $this->createResponse(
                false,
                'User wrong activation code',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
        $isActivated = $accountActivation->activateUser(
            $activationCode,
            $userId
        );

        if ($isActivated) {
            return $this->createResponse(
                true,
                'User is activated',
                Response::HTTP_OK
            );
        } else {
            return $this->createResponse(
                false,
                'User wrong activation code',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    /**
     * @Route("/api/security/forgot-password", name="forgot_password", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $addressEmail = $data['email'];

        $isOK = $this->securityService->resetPassword($addressEmail);

        if ($isOK) {
            return $this->createResponse(
                true,
                'Reset password success',
                Response::HTTP_OK
            );
        } else {
            return $this->createResponse(
                false,
                'Reset password failed',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
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

        $isOK = $this->securityService->changePassword(
            $username,
            $data['newPassword']
        );

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