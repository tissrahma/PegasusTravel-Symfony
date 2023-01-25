<?php
namespace App\Mail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\RawMessage;


class Mails
{
    /**
     * @param MailerInterface $mailer
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(MailerInterface $mailer, string $from, string $to, string $subject, string $body)
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($body);


        $mailer->send($email);


    }
}