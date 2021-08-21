<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use App\Core\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{

    /**
     * @var \App\Core\Domain\Repository\UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var \App\Core\Infrastructure\Security\TokenServiceInterface
     */
    private TokenServiceInterface $tokenService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenServiceInterface   $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService   = $tokenService;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    public function onAuthenticationFailure(
        Request                 $request,
        AuthenticationException $exception
    ): ?Response {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(
        Request        $request,
        TokenInterface $token,
                       $firewallName
    ): ?Response {
        return null;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $authorization = $request->headers->get('Authorization');
        $credentials   = $this->tokenService->decodeToken($authorization);

        if ($this->tokenService->tokenExpired($credentials)) {
            throw new AuthenticationException("Token has expired");
        }

        return new SelfValidatingPassport(
            new UserBadge($credentials->username, function ($userIdentifier) {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            })
        );
    }
}
