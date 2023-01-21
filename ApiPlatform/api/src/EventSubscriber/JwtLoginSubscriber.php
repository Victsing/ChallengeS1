<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events;

class JwtLoginSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnJwtCreated($event): void
    {
        $data = $event->getData();
        $user = $event->getUser();
        $data['id'] = $user->getId();
        $data['firstname'] = $user->getFirstname();
        $data['lastname'] = $user->getLastname();
        $event->setData($data);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onLexikJwtAuthenticationOnJwtCreated'
        ];
    }
}
