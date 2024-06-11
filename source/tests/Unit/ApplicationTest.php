<?php declare(strict_types=1);

namespace Test\Unit;

use App\Application;
use App\DTO\DataDTO;
use App\Exception\DataException;
use App\Provider\BinData\BinDataProviderInterface;
use App\Provider\CurrencyRates\CurrencyRatesProviderInterface;
use App\Provider\Fee\FeeProviderInterface;
use App\Service\DataLoader;
use PHPUnit\Framework\MockObject\Exception;
use Test\AbstractTestCase;

final class ApplicationTest extends AbstractTestCase
{
    /**
     * @throws Exception|DataException
     */
    public function testCalculationSuccess(): void
    {
        $dataLoaderMock = $this->createConfiguredMock(DataLoader::class, [
            'getData' => [
                (new DataDTO())
                    ->setBin('45717360')
                    ->setAmount(50.00)
                    ->setCurrency('USD'),
            ],
        ]);
        $dataLoaderMock->expects($this->once())
            ->method('getData');

        $binDataProviderMock = $this->createConfiguredMock(BinDataProviderInterface::class, [
            'getCountryCode' => 'DK',
        ]);
        $binDataProviderMock->expects($this->once())
            ->method('getCountryCode');

        $currencyRatesProviderMock = $this->createConfiguredMock(
            CurrencyRatesProviderInterface::class,
            [
                'getRate' => 1.074171,
            ]
        );
        $currencyRatesProviderMock->expects($this->once())
            ->method('getRate');

        $feeProviderMock = $this->createConfiguredMock(FeeProviderInterface::class, [
                'getFeeByCountryCode' => 0.01,
            ]
        );
        $feeProviderMock->expects($this->once())
            ->method('getFeeByCountryCode');

        $result = (new Application(
            binDataProvider: $binDataProviderMock,
            currencyRatesProvider: $currencyRatesProviderMock,
            dataLoader: $dataLoaderMock,
            feeProvider: $feeProviderMock
        ))->calculate();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertEquals(0.47, $result[0]);
    }
}
