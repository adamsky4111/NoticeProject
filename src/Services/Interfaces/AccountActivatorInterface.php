<?php

namespace App\Services\Interfaces;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

interface AccountActivatorInterface
{
    public function __construct(EmailServiceInterface $emailService,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator,
                                string $activationAccountEmail);

    public function sendAccountActivationUrl($addressEmail, $userId, $activationCode, $username);

    public function createUniqueKey();

    public function isCodeValid($activationCode, User $user): bool;
}