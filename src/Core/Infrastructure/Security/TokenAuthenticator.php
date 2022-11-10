<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Class TokenAuthenticator
 * @package App\Core\Infrastructure\Security
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class TokenAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    /**
     * @var \App\Core\Infrastructure\Security\TokenServiceInterface
     */
    private TokenServiceInterface $tokenService;

    public function __construct(TokenServiceInterface $tokenService)
    {
        $this->tokenService = $tokenService;
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

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $authorization = $request->headers->get('Authorization');
        $credentials   = $this->tokenService->decodeToken($authorization);

        if ($this->tokenService->tokenExpired($credentials)) {
            throw new AuthenticationException("Token has expired");
        }

        return new SelfValidatingPassport(
            new UserBadge($credentials->username)
        );
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(
            ['message' => 'Full authentication is required to access this resource'],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
