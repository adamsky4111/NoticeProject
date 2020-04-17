<?php

namespace App\Services;

use App\Services\Interfaces\AccountActivationInterface;
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

    public function sendAccountActivationUrl($addressEmail, $userId, $activationCode)
    {
        $link =
            $this->urlGenerator->generate(
                'activate',
                [
                    'code' => $activationCode,
                    'user' => $userId
                ],
                UrlGenerator::ABSOLUTE_URL
            );

        $this->emailService->sendEmail([
            'from' => $this->activationAccountEmail,
            'to' => $addressEmail,
            'subject' => 'Activate your account on Test platform',
            'htmlBody' => 'press the link to activate account: ' . $link,
        ]);

        return true;
    }

    public function createUniqueKey(): string
    {
        return md5(uniqid());
    }
}