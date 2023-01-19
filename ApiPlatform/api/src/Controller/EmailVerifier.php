<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[AsController]
class EmailVerifier extends AbstractController
{

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $em)
    {
    }

    public function __invoke()
    {
        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;
        $userToken = $this->em->getRepository(User::class)->findOneBy(['token' => $token]);
        if (!$userToken) {
            throw new HttpException(401, 'Missing token or password');
        }
        $userToken->setIsVerified(true);
        $userToken->setToken(null);

        $this->em->flush();
        //Envoyer mail avec token
        return $this->json('Success');
    }
}
