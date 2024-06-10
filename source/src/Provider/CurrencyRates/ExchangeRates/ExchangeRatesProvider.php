<?php declare(strict_types=1);

namespace App\Provider\CurrencyRates\ExchangeRates;

use App\Provider\CurrencyRates\CurrencyRatesProviderInterface;
use App\Provider\CurrencyRates\ExchangeRates\Exception\ExchangeRatesException;
use App\Service\SimpleHttpClient;

final class ExchangeRatesProvider implements CurrencyRatesProviderInterface
{
    private const PROVIDER_API_URL = 'http://api.exchangeratesapi.io/latest';
    private ?array $rates = null;

    /**
     * @throws ExchangeRatesException
     */
    public function getRate(string $currencyCode): ?float
    {
        $this->fetchRates();

        return $this->rates[$currencyCode] ?? null;
    }

    /**
     * @throws ExchangeRatesException
     */
    private function fetchRates(): void
    {
        if (null !== $this->rates) {
            return;
        }

        $client = SimpleHttpClient::create();
        $client->submit('GET', sprintf(
            '%s?access_key=%s',
            self::PROVIDER_API_URL,
            getenv('PROVIDER_API_ACCESS_KEY')
        ));

        if (200 !== $client->getStatusCode()) {
            throw new ExchangeRatesException(sprintf(
                'Unexpected status code %d received from remote server.',
                $client->getStatusCode()
            ));
        }

        $response = json_decode($client->getResponseBody(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ExchangeRatesException('Unable to deserialize response.');
        }

        if (false === isset($response['rates']) || false === is_array($response['rates'])) {
            throw new ExchangeRatesException('Unable to fetch currency rates.');
        }

        $this->rates = $response['rates'];
    }
}
