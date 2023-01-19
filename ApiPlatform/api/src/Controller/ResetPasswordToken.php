<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ResetPasswordToken extends AbstractController
{
    private mailer $mailer;

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke()
    {
        $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;
        if (!$email) {
            throw new HttpException(401, 'Missing email');
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $user->setToken(bin2hex(random_bytes(32)));

        $this->em->flush();
        //Envoyer mail avec token
        $message = (new TemplatedEmail())
            ->from('ChallengeS1ESGI@gmail.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->text('Test')
            ->context([
                'url' => "{$_ENV['APP_URL']}/{$user->getToken()}/reset-password",
                'firstname' => $user->getFirstname(),
            ])
            ->htmlTemplate('resetpassword_email.html.twig');
        $this->mailer->send($message);
        return $this->json('Success');
    }
}
