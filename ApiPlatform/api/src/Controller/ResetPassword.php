<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ResetPassword extends AbstractController
{

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $em) {}

    public function __invoke()
    {
        $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $user->setToken(bin2hex(random_bytes(32)));

        $this->em->flush();
        //Envoyer mail avec token
        return $this->json('Success');
    }
}