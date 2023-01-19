<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\JWTService;

final class EmailSubscriber implements EventSubscriberInterface
{
    private mailer $mailer;

    public function __construct(MailerInterface $mailer,  private EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendEmail', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function sendEmail(ViewEvent $event): void
    {
        $jwt = new JWTService();
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = ['user_id'=> $user->getId()];
        $token = $jwt->createToken($header, $payload, $_ENV['JWT_SECRET']);
        $user->setToken($token);
        $this->em->flush();
        $message = (new TemplatedEmail())
            ->from('ChallengeS1ESGI@gmail.com')
            ->to($user->getEmail())
            ->subject('Activation de votre compte sur le site')
            ->text('Test')
            ->htmlTemplate('confirmation_email.html.twig')
            ->context([ 'firstName'=> $user->getFirstname(),
                'token' => $user->getToken()]);
        $this->mailer->send($message);
    }

}
