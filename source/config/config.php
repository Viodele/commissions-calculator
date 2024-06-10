<?php declare(strict_types=1);

return [
    'binDataProvider' => 'App\Provider\BinData\BinListLookup\BinListLookupProvider',
    'currencyRatesProvider' => 'App\Provider\CurrencyRates\ExchangeRates\ExchangeRatesProvider',
    'dataLoader' => 'App\Service\DataLoader',
    'feeProvider' => 'App\Provider\Fee\BaseFeeProvider',
];
