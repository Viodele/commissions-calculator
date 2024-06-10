<?php declare(strict_types=1);

namespace App\Provider\CurrencyRates;

interface CurrencyRatesProviderInterface
{
    public function getRate(string $currencyCode): ?float;
}
