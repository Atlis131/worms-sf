<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;
    private Container $container;

    public function __construct(
        MailerInterface $mailer,
        Container $container,
    )
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function sendEmail($title, $content, $recipient)
    {
        $notification = (new Email())
            ->subject($title)
            ->from(new Address($this->container->getParameter('app.email_verifier_address'), $this->container->getParameter('app.email_verifier_name')))
            ->to($recipient)
            ->html($content);

        $this->mailer->send($notification);

    }
}
