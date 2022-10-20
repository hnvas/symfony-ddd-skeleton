<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Domain\Model;

use App\Core\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package App\Tests\Unit\Core\Domain\Model
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserTest extends TestCase
{

    public function testShouldActivateUser()
    {
        $user = new User('user@email.com', 'username', '123123');

        $user->activate();

        self::assertTrue($user->isActive());
    }

    public function testShouldVerifyUserEmail()
    {
        $user = new User('user@email.com', 'username', '123123');

        $user->verifyEmail();

        self::assertTrue($user->isEmailVerified());
    }

    public function testShouldChangeUserPassword()
    {
        $user = new User('user@email.com', 'username', '123123');
        $newPassword = '54321';

        $user->changePassword($newPassword);

        self::assertEquals($newPassword, $user->password());
    }
}
