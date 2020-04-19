<?php

namespace App\Services\Interfaces;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

interface EmailServiceInterface
{
    public function __construct(MailerInterface $mailer);

    public function sendEmail(Email $email);
}
