<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Action\Auth;

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
 *
 * @Route("/auth/login", name="login", methods={"POST"})
 */
class LoginAction extends AbstractController
{
    /**
     * @var \App\Core\Infrastructure\Security\TokenServiceInterface
     */
    private TokenServiceInterface $tokenService;

    /**
     * PostLoginAction constructor.
     *
     * @param \App\Core\Infrastructure\Security\TokenServiceInterface $tokenService
     */
    public function __construct(TokenServiceInterface $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user    = $this->getUser();
        $payload = new TokenPayload(
            $user->getUserIdentifier(),
            $user->isActive(),
            $user->isEmailVerified(),
            (new DateTimeImmutable())->modify('+12 hours')
        );

        return new JsonResponse([
            "access_token" => $this->tokenService->encodeToken($payload),
            "token_type"   => $this->tokenService->tokenType(),
            "expires_in"   => $payload->expiresIn->getTimestamp(),
            "user_name"    => $payload->username,
            "scope"        => 'all'
        ]);
    }
}
