<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    /**
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHarsher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * AuthController constructor.
     *
     * @param \App\Repository\UserRepository $repository
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHarsher
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordHasherInterface $passwordHarsher,
        EntityManagerInterface $manager
    ) {
        $this->userRepository  = $repository;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(Request $request)
    {
        $userData = json_decode($request->getContent());
        $user     = $this->userRepository->findOneBy([
            'email' => $userData->email,
        ]);

        if (!$user || !$this->passwordHarsher->isPasswordValid($user, $userData->password)) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
        }

        $payload = [
            "email" => $user->getUserIdentifier(),
            "exp"   => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'));

        return $this->json([
            'message' => 'success!',
            'token'   => sprintf('Bearer %s', $jwt),
        ]);
    }
}
