<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Services\UseCases;

use App\Core\Application\Exceptions\ApplicationException;
use App\Core\Application\Services\UseCases\VerifyUserEmail;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use SymfonyCasts\Bundle\VerifyEmail\Exception\InvalidSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Class VerifyUserEmailTest
 * @package App\Tests\Unit\Core\Application\UseCases
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class VerifyUserEmailTest extends TestCase
{

    private VerifyEmailHelperInterface $verifyEmailHelperMock;
    private UserRepositoryInterface    $userRepositoryMock;
    private VerifyUserEmail            $useCase;

    public function setUp(): void
    {
        $this->verifyEmailHelperMock = self::createMock(
            VerifyEmailHelperInterface::class
        );

        $this->userRepositoryMock = self::createMock(
            UserRepositoryInterface::class
        );

        $this->useCase = new VerifyUserEmail(
            $this->verifyEmailHelperMock,
            $this->userRepositoryMock
        );
    }

    /** @dataProvider userWithId */
    public function testShouldVerifyUserEmail(User $user)
    {
        $this->verifyEmailHelperMock->expects(self::once())
                                    ->method('validateEmailConfirmation');

        $this->userRepositoryMock->expects(self::once())
                                 ->method('flush');

        $signedUrl = 'https://www.host.com/?validSignedToken=ca2u39dsdu123';

        $this->useCase->execute($signedUrl, $user);
    }

    /** @dataProvider userWithId */
    public function testShouldThrowAnExceptionWhenTokenIsInvalid(User $user)
    {
        $signedUrl = 'https://www.host.com/?validSignedToken=ca2u39dsdu123';

        $this->verifyEmailHelperMock->expects(self::once())
                                    ->method('validateEmailConfirmation')
                                    ->willThrowException(new InvalidSignatureException());

        self::expectException(ApplicationException::class);
        self::expectExceptionMessage('Invalid Token');

        $this->useCase->execute($signedUrl, $user);
    }

    public function userWithId(): array
    {
        $user = new User('user@email.com', 'username', '123123');

        $userReflection = new \ReflectionClass($user);
        $idProperty     = $userReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($user, 1);

        return [
            ['user' => $user]
        ];
    }
}
