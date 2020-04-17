<?php

namespace App\Services;

use App\Services\Interfaces\AccountActivationInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountActivation implements AccountActivationInterface
{
    private $urlGenerator;

    private $emailService;

    private $translator;

    private $activationAccountEmail;

    public function __construct(EmailService $emailService,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator,
                                string $activationAccountEmail)
    {
        $this->emailService = $emailService;
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
                UrlGenerator::ABSOLUTE_URL
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
}