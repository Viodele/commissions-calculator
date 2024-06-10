<?php declare(strict_types=1);

namespace App;

use App\Exception\DataException;
use App\Provider\BinData\BinDataProviderInterface;
use App\Provider\CurrencyRates\CurrencyRatesProviderInterface;
use App\Provider\Fee\FeeProviderInterface;
use App\Service\DataLoader;

readonly class Application
{
    public const BASE_CURRENCY_CODE = 'EUR';

    private BinDataProviderInterface $binDataProvider;
    private CurrencyRatesProviderInterface $currencyRatesProvider;
    private DataLoader $dataLoader;
    private FeeProviderInterface $feeProvider;

    public function __construct(
        private array $configuration = []
    ) {
        $binDataProvider = $this->configuration['binDataProvider'];
        $this->binDataProvider = new $binDataProvider();

        $currencyRatesProvider = $this->configuration['currencyRatesProvider'];
        $this->currencyRatesProvider = new $currencyRatesProvider();

        $dataLoader = $this->configuration['dataLoader'];
        $this->dataLoader = new $dataLoader();

        $feeProvider = $this->configuration['feeProvider'];
        $this->feeProvider = new $feeProvider();
    }

    /**
     * @throws DataException
     */
    public function execute(): void
    {
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

            $commission = $fixedAmount * $this->feeProvider->getFeeByCountryCode(
                $this->binDataProvider->getCountryCode($row->getBin())
            );

            echo $commission . PHP_EOL;
        }
    }
}
