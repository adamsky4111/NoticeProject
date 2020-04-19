<?php

namespace App\Services\Interfaces;

use Symfony\Contracts\Translation\TranslatorInterface;

interface RestorePasswordServiceInterface
{
    public function __construct(EmailServiceInterface $emailService,
                                TranslatorInterface $translator,
                                string $restoreEmail);
    
    public function sendNewPassword($addressEmail, $username);

    public function generateTemporaryPassword();
}