<?php declare(strict_types=1);

namespace App;

use App\DTO\DataDTO;
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
        $this->binDataProvider = new $this->configuration['binDataProvider']();
        $this->currencyRatesProvider = new $this->configuration['currencyRatesProvider']();
        $this->dataLoader = new $this->configuration['dataLoader']();
        $this->feeProvider = new $this->configuration['feeProvider']();
    }

    /**
     * @throws DataException
     */
    public function execute(): void
    {
        /** @var $rows DataDTO[] */
        $rows = $this->dataLoader->getData();
        foreach ($rows as $row) {
            $countryCode = $this->binDataProvider->getCountryCode($row->getBin()); // TODO: Remove me
            $exchangeRate = $this->currencyRatesProvider->getRate($row->getCurrency());

            if (
                self::BASE_CURRENCY_CODE == $row->getCurrency()
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
