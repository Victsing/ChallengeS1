<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\Voter\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class UserSubscriber implements EventSubscriberInterface
{
    /*
        private $mailer;

        public function __construct(MailerInterface $mailer)
        {
            $this->mailer = $mailer;
        }
    */
    //private EmailVerifier $emailVerifier;
    private  mailer $mailer;

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

    public static function sendEmailValidation(){
        return [
            KernelEvents::VIEW => ['sendEmail', EventPriorities::POST_WRITE]
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
            ->to('ChallengeS1ESGI@gmail.com')
            ->subject('test')
            ->text(sprintf('Test'))
            ->htmlTemplate('confirmation_email.html.twig');
        $this->mailer->send($message);
    }

}
