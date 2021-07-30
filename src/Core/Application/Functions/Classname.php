<?php
declare(strict_types = 1);

namespace App\Core\Application\Functions;

class Classname
{

    /**
     * Provides the base name of any class
     *
     * @param string $classpath
     *
     * @return string
     */
    public static function getBaseName(string $classpath): string
    {
        $path = explode('\\', $classpath);

        return array_pop($path);
    }

}
