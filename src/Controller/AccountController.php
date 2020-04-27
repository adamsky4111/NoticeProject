<?php

namespace App\Controller;

use App\Services\Interfaces\AccountManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        } else {
            return $this->createResponse(
                false,
                'User account update failed',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    /**
     * @Route("/my-notices", name="get_my_notices")
     */
    public function getMyNotices()
    {
        $user = $this->getUser();
        if ($user instanceof UserInterface && $user->getAccount !== null) {
            $notices = $user->getAccount->getNotices();
            $data = [];

            foreach ($notices as $notice) {
                $data[] = $notice->toArray();
            }

            return new JsonResponse($data, Response::HTTP_FOUND);
        }

        return $this->createResponse(
            false,
            'Wrong user or no account',
            Response::HTTP_NOT_ACCEPTABLE
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
