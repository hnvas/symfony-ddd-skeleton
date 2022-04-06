<?php
declare(strict_types = 1);

namespace App\Core\Application\UseCases;

use App\Core\Application\Exceptions\InvalidDataException;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\UserRepositoryInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmail
{

    private VerifyEmailHelperInterface $verifyEmailHelper;
    private UserRepositoryInterface    $userRepository;

    public function __construct(
        VerifyEmailHelperInterface $verifyEmailHelper,
        UserRepositoryInterface    $userRepository
    ) {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->userRepository    = $userRepository;
    }

    /**
     * @throws \App\Core\Application\Exceptions\InvalidDataException
     */
    public function execute(string $signedUrl, User $user): void
    {
        try {
            $this->verifyEmailHelper->validateEmailConfirmation(
                $signedUrl,
                strval($user->id()),
                $user->getUserIdentifier()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            throw new InvalidDataException("Invalid Token", [$e->getReason()]);
        }

        $user->verifyEmail();
        $this->userRepository->flush();
    }
}
