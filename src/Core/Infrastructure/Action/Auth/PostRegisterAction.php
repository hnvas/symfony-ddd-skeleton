<?php

namespace App\Core\Infrastructure\Action\Auth;

use App\Core\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PostRegisterAction
 * @package App\Core\Infrastructure\Action\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/auth/register", name="register", methods={"POST"})
 */
class PostRegisterAction
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
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * PostRegisterAction constructor.
     *
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHarsher
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHarsher, EntityManagerInterface $manager,
        SerializerInterface $serializer
    ) {
        $this->passwordHarsher = $passwordHarsher;
        $this->manager         = $manager;
        $this->serializer      = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Core\Domain\Entity\User $userData */
        $user = $this->serializer->deserialize(
            $request->getContent(), User::class, 'json'
        );

        $user->setPassword(
            $this->passwordHarsher->hashPassword($user, $user->getPassword())
        );

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse([
            'user' => $user->getEmail()
        ]);
    }
}
