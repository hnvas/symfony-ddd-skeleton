<?php

namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
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
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var \App\Security\TokenServiceInterface
     */
    private TokenServiceInterface $tokenService;

    public function __construct(
        UserRepository $userRepository,
        TokenServiceInterface $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        return new JsonResponse([
            'message' => 'Invalid token'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $firewallName
    ): ?Response {
        return null;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $authorization = $request->headers->get('Authorization');
        $identifier    = $this->getIdentifier($authorization);

        return new SelfValidatingPassport(
            new UserBadge($identifier, function ($userIdentifier) {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            })
        );
    }

    private function getIdentifier(string $authorization): string
    {
        try {
            $credentials = $this->tokenService->decodeToken($authorization);

            return $credentials->username;
        } catch (\Exception $ex) {
            return '';
        }
    }
}
