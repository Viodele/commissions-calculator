<?php declare(strict_types=1);

namespace App\Provider\Fee;

interface FeeProviderInterface
{
    public function getFeeByCountryCode(string $countryCode): float;
}
