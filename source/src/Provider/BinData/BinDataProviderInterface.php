<?php declare(strict_types=1);

namespace App\Provider\BinData;

interface BinDataProviderInterface
{
    public function getCountryCode(string $bin): string;
}
