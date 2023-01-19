<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JWTSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnAuthenticationSuccess($event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User || !$user->isVerified()) {
            throw new HttpException(403, 'User not verified');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onLexikJwtAuthenticationOnAuthenticationSuccess'
        ];
    }
}
