<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Services\UseCases;

use App\Core\Application\Services\UseCases\SendUserEmailVerification;
use App\Core\Domain\Model\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Class SendUserEmailVerificationTest
 * @package App\Tests\Unit\Core\Application\UseCases
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class SendUserEmailVerificationTest extends TestCase
{

    private VerifyEmailHelperInterface $verifyEmailHelperMock;
    private MailerInterface            $mailerMock;
    private ContainerBagInterface      $containerBagMock;

    public function setUp(): void
    {
        $this->containerBagMock = self::createMock(ContainerBag::class);
        $this->mailerMock            = self::createMock(MailerInterface::class);
        $this->verifyEmailHelperMock = self::createMock(
            VerifyEmailHelperInterface::class
        );
    }

    /** @dataProvider userWithId */
    public function testShouldSendAnEmailToVerifyUser(User $user)
    {
        $verifyEmailSignatureComponents = new VerifyEmailSignatureComponents(
            new \DateTimeImmutable('tomorrow'),
            'http://localhost:4200/verify?token=fj2jr834rj44rh4a07dfhsadasd9sad',
            time()
        );

        $this->containerBagMock->expects(self::exactly(2))
                               ->method('get')
                               ->withConsecutive(['client_host'], ['client_scheme'])
                               ->willReturnOnConsecutiveCalls('localhost:4200', 'http');

        $this->verifyEmailHelperMock->expects(self::once())
                                    ->method('generateSignature')
                                    ->willReturn($verifyEmailSignatureComponents);

        $this->mailerMock->expects(self::once())->method('send');

        $useCase = new SendUserEmailVerification(
            $this->verifyEmailHelperMock,
            $this->mailerMock,
            $this->containerBagMock
        );

        $useCase->execute($user);
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
