<?php

namespace App\Controller;

use App\Services\Interfaces\AccountManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/account")
 */
class AccountController extends AbstractController
{
    private $accountManager;

    private $translator;

    public function __construct(AccountManagerInterface $accountManager,
                                TranslatorInterface $translator)
    {
        $this->accountManager = $accountManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/edit", name="edit_account")
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        if ($account = $user->getAccount()) {

            $data = json_decode($request->getContent(), true);
            $this->accountManager->updateAccount($data, $account);

            return $this->createResponse(
                true,
                'User account update success',
                Response::HTTP_OK
            );
        }
        else {
            return $this->createResponse(
                false,
                'User account update failed',
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
