<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Domain\Functions;

use App\Core\Application\Services\CRUD\CrudService;
use App\Core\Domain\Functions\ClassName;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Security\JwtTokenService;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassNameTest
 * @package App\Tests\Unit\Core\Domain\Functions
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ClassNameTest extends TestCase
{

    /** @dataProvider pathCasesToGetBaseName */
    public function testGetBaseName(string $expected, string $input)
    {
        $result = ClassName::getBaseName($input);

        self::assertEquals($expected, $result);
    }

    public function pathCasesToGetBaseName(): array
    {
        return [
            'should return class name User' => [
                'expected' => 'User',
                'input' => User::class
            ],
            'should return class name CrudFacade' => [
                'expected' => 'CrudFacade',
                'input' => CrudService::class
            ],
            'should return class name JwtTokenService' => [
                'expected' => 'JwtTokenService',
                'input' => JwtTokenService::class
            ]
        ];
    }

}
