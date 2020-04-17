<?php

namespace App\Services\Interfaces;

use App\Services\EmailService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

interface AccountActivationInterface
{
    public function __construct(EmailService $emailService,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator,
                                string $activationAccountEmail);

    public function sendAccountActivationUrl($addressEmail, $userId, $activationCode);

    public function createUniqueKey();
}