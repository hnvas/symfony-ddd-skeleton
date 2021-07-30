<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use Exception;

class ApplicationException extends Exception
{

    private array $details;

    /**
     * @param string $message
     * @param array $details
     */
    public function __construct(string $message = "", array $details = [])
    {
        $this->details = $details;

        parent::__construct($message);
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

}
