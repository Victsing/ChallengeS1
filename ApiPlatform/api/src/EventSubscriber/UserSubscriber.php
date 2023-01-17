<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updatePassword', EventPriorities::PRE_WRITE],
        ];
    }

    public function updatePassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        dump($event->getRequest());

        if (!$user instanceof User || Request::METHOD_PATCH !== $method) {
            return;
        }

        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
    }

    public function sendMail(ViewEvent $event): void
    {
        // TODO: Implement sendMail() method.
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $email = (new TemplatedEmail())
            ->from('heh@jdj.com')
            ->to('hjfkd@jkdj.com')
            ->subject('Veuillez Confirmer votre Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        if (
            $user instanceof User
            && Request::METHOD_POST === $method
        ) {
            $this->mailer->send($email);
        }

    }


}
