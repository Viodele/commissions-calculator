<?php declare(strict_types=1);

namespace App\Provider\BinData\BinListLookup\Exception;

use Exception;

final class BinListLookupException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500);
    }
}
