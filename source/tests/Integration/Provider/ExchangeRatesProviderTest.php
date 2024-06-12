<?php declare(strict_types=1);

namespace Test\Integration\Provider;

use App\Provider\CurrencyRates\ExchangeRates\Exception\ExchangeRatesException;
use App\Provider\CurrencyRates\ExchangeRates\ExchangeRatesProvider;
use PHPUnit\Framework\TestCase;

final class ExchangeRatesProviderTest extends TestCase
{
    private const INVALID_ACCESS_KEY_VALUE = 'not_a_valid_token';

    /**
     * @throws ExchangeRatesException
     */
    public function testGetExchangeRatesSuccess(): void
    {
        $exchangeRatesProvider = new ExchangeRatesProvider();
        $rate = $exchangeRatesProvider->getRate('EUR');

        $this->assertIsFloat($rate);
    }

    public function testWrongAccessKeyFailure(): void
    {
        $accessToken = getenv('PROVIDER_API_ACCESS_KEY');
        putenv(sprintf('PROVIDER_API_ACCESS_KEY=%s', self::INVALID_ACCESS_KEY_VALUE));

        $this->expectException(ExchangeRatesException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Wrong access key.');

        $exchangeRatesProvider = new ExchangeRatesProvider();
        $exchangeRatesProvider->getRate('EUR');

        putenv(sprintf('PROVIDER_API_ACCESS_KEY=%s', $accessToken));
    }
}
