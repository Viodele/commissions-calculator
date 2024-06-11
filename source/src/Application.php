<?php declare(strict_types=1);

namespace App;

use App\Exception\DataException;
use App\Provider\BinData\BinDataProviderInterface;
use App\Provider\CurrencyRates\CurrencyRatesProviderInterface;
use App\Provider\Fee\FeeProviderInterface;
use App\Service\DataLoader;

final readonly class Application
{
    public const BASE_CURRENCY_CODE = 'EUR';

    public function __construct(
        private BinDataProviderInterface $binDataProvider,
        private CurrencyRatesProviderInterface $currencyRatesProvider,
        private DataLoader $dataLoader,
        private FeeProviderInterface $feeProvider
    ) {
    }

    /**
     * @throws DataException
     */
    public function calculate(): array
    {
        $results = [];

        $rows = $this->dataLoader->getData();
        foreach ($rows as $row) {
            $exchangeRate = $this->currencyRatesProvider->getRate($row->getCurrency());

            if (
                self::BASE_CURRENCY_CODE === $row->getCurrency()
                || 0.0 === $exchangeRate
            ) {
                $fixedAmount = $row->getAmount();
            } else {
                $fixedAmount = $row->getAmount() / $exchangeRate;
            }

            $results[] = round($fixedAmount * $this->feeProvider->getFeeByCountryCode(
                $this->binDataProvider->getCountryCode($row->getBin())
            ), 2);
        }

        return $results;
    }
}
