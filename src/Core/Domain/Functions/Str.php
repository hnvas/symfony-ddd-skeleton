<?php
declare(strict_types = 1);

namespace App\Core\Domain\Functions;

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
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
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
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

}
