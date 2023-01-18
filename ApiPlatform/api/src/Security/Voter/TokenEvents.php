<?php
namespace Security\Voter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiController extends Controller
{
public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
{
// ...

return new JsonResponse(['token' => $JWTManager->create($user)]);
}
}