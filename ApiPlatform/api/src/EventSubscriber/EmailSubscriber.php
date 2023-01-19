<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EmailSubscriber implements EventSubscriberInterface
{
    private mailer $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendEmail', EventPriorities::POST_WRITE]
        ];
    }
    public function sendEmail(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        dump($event->getRequest());

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }
        $message = (new TemplatedEmail())
            ->from('ChallengeS1ESGI@gmail.com')
            ->to($user->getEmail())
            ->subject('test')
            ->text('Test')
            ->htmlTemplate('confirmation_email.html.twig');
        $this->mailer->send($message);
    }

}
