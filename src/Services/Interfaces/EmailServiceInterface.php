<?php

namespace App\Services\Interfaces;

use Symfony\Component\Mailer\MailerInterface;

interface EmailServiceInterface
{
    public function __construct(MailerInterface $mailer);

    public function sendEmail($data);

    public function createEmail($data);
}