<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Security\TokenPayload;
use App\Core\Infrastructure\Security\TokenServiceInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LoginAction
 * @package App\Core\Infrastructure\Action\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
#[Route('/login', name: 'login_action', methods: ['POST'])]
class LoginAction extends AbstractController
{

    public function __construct(
        private readonly TokenServiceInterface $tokenService
    ) {}

    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user    = $this->getUser()->model();
        $payload = new TokenPayload(
            $user->email(),
            $user->isActive(),
            $user->isEmailVerified(),
            (new DateTimeImmutable())->modify('+12 hours')
        );

        return new JsonResponse([
            "access_token" => $this->tokenService->encodeToken($payload),
            "token_type"   => $this->tokenService->tokenType(),
            "expires_in"   => $payload->expiresIn->getTimestamp(),
            "user_name"    => $payload->username
        ]);
    }
}
