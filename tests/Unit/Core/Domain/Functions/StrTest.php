<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Domain\Functions;

use App\Core\Domain\Functions\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class StrTest
 * @package App\Tests\Unit\Core\Domain\Functions
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class StrTest extends TestCase
{

    /** @dataProvider stringCasesToSnake */
    public function testSnake(string $expected, string $input)
    {
        $result = Str::snake($input);

        self::assertEquals($expected, $result);
    }

    /** @dataProvider stringCasesToCamel */
    public function testCamel(string $expected, string $input)
    {
        $result = Str::camel($input);

        self::assertEquals($expected, $result);
    }

    public function stringCasesToSnake(): array
    {
        return [
            'should transform camel into snake' => [
                'expected' => 'should_transform_camel_into_snake',
                'input' => 'ShouldTransformCamelIntoSnake'
            ],
            'should transform spaces into snake' =>[
                'expected' => 'should_transform_spaces_into_snake',
                'input' => 'Should transform spaces into snake'
            ],
            'should keep snake as it is' =>[
                'expected' => 'should_keep_snake_as_it_is',
                'input' => 'should_keep_snake_as_it_is'
            ],
        ];
    }

    public function stringCasesToCamel(): array
    {
        return [
            'should transform snake into camel' => [
                'expected' => 'ShouldTransformSnakeIntoCamel',
                'input' => 'should_transform_snake_into_camel',
            ],
            'should transform spaces into camel' =>[
                'expected' => 'ShouldTransformSpacesIntoCamel',
                'input' => 'Should_transform_spaces_into_camel'
            ],
            'should keep camel as it is' =>[
                'expected' => 'ShouldKeepCamelAsItIs',
                'input' => 'ShouldKeepCamelAsItIs'
            ],
        ];
    }

}
