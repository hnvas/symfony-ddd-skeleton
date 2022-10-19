<?php
declare(strict_types = 1);

namespace App\Core\Domain\Functions;

/**
 * Class Str
 * @package App\Core\Domain\Functions
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class Str
{

    /**
     * Convert strings into snake case
     *
     * @param $input
     *
     * @return string
     */
    public static function snake($input): string
    {
        return strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                str_replace(' ', '_', $input)
            )
        );
    }

    /**
     * Convert strings into camel case
     *
     * @param $input
     *
     * @return string
     */
    public static function camel($input): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
    }

}
