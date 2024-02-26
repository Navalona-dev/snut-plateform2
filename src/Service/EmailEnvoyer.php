<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailEnvoyer
{
    public function __construct(private MailerInterface $mailer)
    {
        
    }

    public function sendEmailUser($from, $to, $subject, $template, $data)
    {
        $mail = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($data)
        ;

        $this->mailer->send($mail);
    }
}