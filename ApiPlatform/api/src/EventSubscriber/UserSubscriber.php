<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updatePassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function updatePassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        // dump($user);
        if ($user instanceof User && ($method === "POST" || $method === "PATCH")) {
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
        }
    }

}
