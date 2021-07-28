<?php

namespace App\Core\Infrastructure\Controller;

use App\Core\Domain\Entity\User;
use App\Core\Infrastructure\Security\TokenPayload;
use App\Core\Infrastructure\Security\TokenServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHarsher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var \App\Core\Infrastructure\Security\TokenServiceInterface
     */
    private TokenServiceInterface $tokenService;

    /**
     * AuthController constructor.
     *
     * @param \App\Core\Infrastructure\Security\TokenServiceInterface $tokenService
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHarsher
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function __construct(
        TokenServiceInterface $tokenService,
        UserPasswordHasherInterface $passwordHarsher,
        EntityManagerInterface $manager
    ) {
        $this->tokenService    = $tokenService;
        $this->passwordHarsher = $passwordHarsher;
        $this->manager         = $manager;
    }

    /**
     * @Route("/auth/register", name="register", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $userData = json_decode($request->getContent());
        $password = $userData->password;
        $email    = $userData->email;
        $name     = $userData->name;

        $user = new User();
        $user->setPassword($this->passwordHarsher->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setName($name);

        $this->manager->persist($user);
        $this->manager->flush();

        return $this->json([
            'user' => $user->getEmail()
        ]);
    }

    /**
     * @Route("/auth/login", name="login", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(): JsonResponse
    {
        $payload = new TokenPayload(
            $this->getUser()->getUserIdentifier(),
            (new \DateTimeImmutable())->modify('+12 hours')
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
