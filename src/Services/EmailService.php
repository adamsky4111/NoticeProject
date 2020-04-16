<?php

namespace App\Services;

use App\Services\Interfaces\EmailServiceInterface;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService implements EmailServiceInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($data)
    {
        $email = $this->createEmail($data);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new JsonException($e->getMessage());
        }
    }

    function createEmail($data)
    {
        return (new Email())
            ->from($data['from'])
            ->to($data['to'])
            ->subject($data['subject'])
            ->text($data['text'])
            ->html($data['htmlBody']);
    }
}