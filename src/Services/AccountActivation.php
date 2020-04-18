<?php

namespace App\Services;

use App\Services\Interfaces\AccountActivationInterface;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountActivation implements AccountActivationInterface
{
    private $urlGenerator;

    private $emailService;

    private $translator;

    private $activationAccountEmail;

    private $userService;

    public function __construct(EmailServiceInterface $emailService,
                                UserServiceInterface $userService,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator,
                                string $activationAccountEmail)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->activationAccountEmail = $activationAccountEmail;
    }

    public function sendAccountActivationUrl($addressEmail,
                                             $userId,
                                             $activationCode,
                                             $username)
    {
        $link =
            $this->urlGenerator->generate(
                'activate_user',
                [
                    'code' => $activationCode,
                    'user' => $userId
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        $context = [
            'link' => $link,
            'username' => $username,
        ];

        $subject = $this->translator->trans('User activation email subject');
        $activationEmail = (new TemplatedEmail())
            ->from($this->activationAccountEmail)
            ->to($addressEmail)
            ->subject($subject)
            ->htmlTemplate('email/activation.html.twig')
            ->context($context);

        $this->emailService->sendEmail($activationEmail);

        return true;
    }

    public function createUniqueKey(): string
    {
        return md5(uniqid());
    }

    public function activateUser($activationCode, $userId)
    {
        $user = $this->userService->getUserById($userId);
        $userCode = $user->getActivationCode();
        if ($userCode === $activationCode) {
            $user->setActive(true);
            return true;
        }

        return false;
    }
}