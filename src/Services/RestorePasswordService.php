<?php

namespace App\Services;

use App\Entity\User;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\RestorePasswordServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;

class RestorePasswordService implements RestorePasswordServiceInterface
{
    private $emailService;

    private $translator;

    private $restoreEmail;

    public function __construct(EmailServiceInterface $emailService,
                                TranslatorInterface $translator,
                                string $restoreEmail)
    {
        $this->emailService = $emailService;
        $this->translator = $translator;
        $this->restoreEmail = $restoreEmail;
    }

    public function sendAndGenerateNewPassword($addressEmail, $username)
    {
        $temporaryPassword = $this->generateTemporaryPassword();
        $subject = $this->translator->trans('User restore password email subject');
        $context[] = [
            'temporaryPassword' => $temporaryPassword,
            'username' => $username,
        ];

        $activationEmail = (new TemplatedEmail())
            ->from($this->restoreEmail)
            ->to($addressEmail)
            ->subject($subject)
            ->htmlTemplate('email/restorePassword.html.twig')
            ->context($context);

        $this->emailService->sendEmail($activationEmail);

        return $temporaryPassword;
    }

    public function generateTemporaryPassword()
    {
        return uniqid();
    }
}