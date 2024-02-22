<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;
    private Container       $container;

    public function __construct(
        MailerInterface $mailer,
        Container       $container,
    )
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        $title,
        $content,
        $recipient
    ): void
    {
        $notification = (new Email())
            ->subject($title)
            ->from(new Address($this->container->getParameter('email')['username'], $this->container->getParameter('email')['name']))
            ->to($recipient)
            ->html($content);

        $this->mailer->send($notification);
    }
}
