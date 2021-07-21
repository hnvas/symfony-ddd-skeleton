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

class JwtAuthenticator extends AbstractAuthenticator
{

    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface
     */
    private ContainerBagInterface $params;

    /**
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
        ContainerBagInterface $params
    ) {
        $this->userRepository = $userRepository;
        $this->params         = $params;
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
        $providerKey
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
            $token       = str_replace('Bearer ', '', $authorization);
            $secret      = $this->params->get('jwt_secret');
            $credentials = JWT::decode($token, $secret, ['HS256']);

            return $credentials->email;
        } catch (\Exception $ex) {
            return '';
        }
    }
}
