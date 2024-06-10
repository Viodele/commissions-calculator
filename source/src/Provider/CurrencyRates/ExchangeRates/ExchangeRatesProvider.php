<?php declare(strict_types=1);

namespace App\Provider\CurrencyRates\ExchangeRates;

use App\Provider\CurrencyRates\CurrencyRatesProviderInterface;
use App\Provider\CurrencyRates\ExchangeRates\Exception\ExchangeRatesException;
use App\Service\SimpleHttpClient;

final class ExchangeRatesProvider implements CurrencyRatesProviderInterface
{
    private const PROVIDER_API_URL = 'http://api.exchangeratesapi.io/latest';
    private const PROVIDER_API_ACCESS_KEY = 'security_code';
    private ?array $rates = null;

    /**
     * @throws ExchangeRatesException
     */
    public function getRate(string $currencyCode): ?float
    {
        $this->rates = [ # TODO: REMOVE ME
            'AED' => 3.945456,
            'AFN' => 75.480049,
            'ALL' => 100.324416,
            'AMD' => 416.284027,
            'ANG' => 1.934589,
            'AOA' => 915.713617,
            'ARS' => 968.359741,
            'AUD' => 1.627322,
            'AWG' => 1.936193,
            'AZN' => 1.827641,
            'BAM' => 1.955283,
            'BBD' => 2.167327,
            'BDT' => 126.126662,
            'BGN' => 1.955854,
            'BHD' => 0.404893,
            'BIF' => 3111.806444,
            'BMD' => 1.074171,
            'BND' => 1.453029,
            'BOB' => 7.417109,
            'BRL' => 5.767656,
            'BSD' => 1.073416,
            'BTC' => 1.5430043E-5,
            'BTN' => 89.649138,
            'BWP' => 14.74424,
            'BYN' => 3.512304,
            'BYR' => 21053.752988,
            'BZD' => 2.163648,
            'CAD' => 1.478972,
            'CDF' => 3037.756085,
            'CHF' => 0.963457,
            'CLF' => 0.035894,
            'CLP' => 990.418162,
            'CNY' => 7.785375,
            'CNH' => 7.807785,
            'COP' => 4259.195713,
            'CRC' => 568.349772,
            'CUC' => 1.074171,
            'CUP' => 28.465533,
            'CVE' => 110.235862,
            'CZK' => 24.662219,
            'DJF' => 191.121261,
            'DKK' => 7.458915,
            'DOP' => 64.303003,
            'DZD' => 144.819796,
            'EGP' => 51.240198,
            'ERN' => 16.112566,
            'ETB' => 61.32906,
            'EUR' => 1,
            'FJD' => 2.407486,
            'FKP' => 0.855147,
            'GBP' => 0.844619,
            'GEL' => 3.034531,
            'GGP' => 0.855147,
            'GHS' => 15.993921,
            'GIP' => 0.855147,
            'GMD' => 72.801905,
            'GNF' => 9243.556706,
            'GTQ' => 8.340795,
            'GYD' => 224.6927,
            'HKD' => 8.393138,
            'HNL' => 26.522989,
            'HRK' => 7.497667,
            'HTG' => 142.382365,
            'HUF' => 393.945835,
            'IDR' => 17517.581823,
            'ILS' => 4.027091,
            'IMP' => 0.855147,
            'INR' => 89.721747,
            'IQD' => 1405.128591,
            'IRR' => 45222.602439,
            'ISK' => 149.717811,
            'JEP' => 0.855147,
            'JMD' => 166.825904,
            'JOD' => 0.761372,
            'JPY' => 168.526167,
            'KES' => 138.994829,
            'KGS' => 93.464054,
            'KHR' => 4452.864455,
            'KMF' => 486.008598,
            'KPW' => 966.753638,
            'KRW' => 1477.801538,
            'KWD' => 0.329437,
            'KYD' => 0.894572,
            'KZT' => 481.647173,
            'LAK' => 23282.062736,
            'LBP' => 96124.186938,
            'LKR' => 325.363998,
            'LRD' => 208.186909,
            'LSL' => 20.132379,
            'LTL' => 3.171748,
            'LVL' => 0.649755,
            'LYD' => 5.189628,
            'MAD' => 10.715168,
            'MDL' => 18.999155,
            'MGA' => 4844.764515,
            'MKD' => 61.585821,
            'MMK' => 3015.55329,
            'MNT' => 3705.889951,
            'MOP' => 8.639397,
            'MRU' => 42.096873,
            'MUR' => 50.053384,
            'MVR' => 16.549866,
            'MWK' => 1861.125386,
            'MXN' => 19.823934,
            'MYR' => 5.071186,
            'MZN' => 68.213578,
            'NAD' => 20.132379,
            'NGN' => 1613.265394,
            'NIO' => 39.509557,
            'NOK' => 11.48747,
            'NPR' => 143.438421,
            'NZD' => 1.756855,
            'OMR' => 0.413517,
            'PAB' => 1.073426,
            'PEN' => 4.027335,
            'PGK' => 4.179895,
            'PHP' => 63.097345,
            'PKR' => 298.620967,
            'PLN' => 4.328301,
            'PYG' => 8077.864826,
            'QAR' => 3.913965,
            'RON' => 4.977663,
            'RSD' => 117.065334,
            'RUB' => 95.681805,
            'RWF' => 1408.340853,
            'SAR' => 4.028404,
            'SBD' => 9.111659,
            'SCR' => 14.491331,
            'SDG' => 629.464014,
            'SEK' => 11.307917,
            'SGD' => 1.453853,
            'SHP' => 1.357161,
            'SLE' => 24.541912,
            'SLL' => 22524.831278,
            'SOS' => 613.443563,
            'SRD' => 33.991605,
            'STD' => 22233.172423,
            'SVC' => 9.392517,
            'SYP' => 2698.886885,
            'SZL' => 20.122868,
            'THB' => 39.485488,
            'TJS' => 11.447981,
            'TMT' => 3.77034,
            'TND' => 3.347715,
            'TOP' => 2.538378,
            'TRY' => 34.808833,
            'TTD' => 7.276144,
            'TWD' => 34.764446,
            'TZS' => 2808.957568,
            'UAH' => 43.438114,
            'UGX' => 4051.928978,
            'USD' => 1.074171,
            'UYU' => 41.698978,
            'UZS' => 13575.411693,
            'VEF' => 3891241.134932,
            'VES' => 39.134804,
            'VND' => 27308.114047,
            'VUV' => 127.527727,
            'WST' => 3.011001,
            'XAF' => 655.789764,
            'XAG' => 0.036409,
            'XAU' => 0.000466,
            'XCD' => 2.903001,
            'XDR' => 0.809194,
            'XOF' => 655.783661,
            'XPF' => 119.331742,
            'YER' => 268.999291,
            'ZAR' => 20.199895,
            'ZMK' => 9668.826535,
            'ZMW' => 28.39176,
            'ZWL' => 345.882647,
        ];

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
            self::PROVIDER_API_ACCESS_KEY
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
