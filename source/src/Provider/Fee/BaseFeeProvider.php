<?php declare(strict_types=1);

namespace App\Provider\Fee;

use App\Provider\Fee\FeeProviderInterface;

class BaseFeeProvider implements FeeProviderInterface
{
    private const FEE_MAPPING = [
        [
            'countries' => [
                'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE',
                'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT',
                'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO',
                'SE', 'SI', 'SK',
            ],
            'fee' => 0.01,
        ],
    ];
    private const DEFAULT_FEE = 0.02;

    public function getFeeByCountryCode(string $countryCode): float
    {
        foreach (self::FEE_MAPPING as $region) {
            if (true === in_array($countryCode, $region['countries'], true)) {
                return $region['fee'];
            }
        }

        return self::DEFAULT_FEE;
    }
}
