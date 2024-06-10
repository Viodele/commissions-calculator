<?php declare(strict_types=1);

namespace App\Exception;

use Exception;

final class DataException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500);
    }
}
