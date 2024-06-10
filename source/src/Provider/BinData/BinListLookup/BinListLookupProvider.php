<?php declare(strict_types=1);

namespace App\Provider\BinData\BinListLookup;

use App\Provider\BinData\BinDataProviderInterface;
use App\Provider\BinData\BinListLookup\Exception\BinListLookupException;
use App\Service\SimpleHttpClient;

final class BinListLookupProvider implements BinDataProviderInterface
{
    private const PROVIDER_API_URL = 'https://lookup.binlist.net/';

    /**
     * @throws BinListLookupException
     */
    public function getCountryCode(string $bin): string
    {
        $client = SimpleHttpClient::create();
        $client->submit(method: 'GET', url: self::PROVIDER_API_URL . $bin);

        if (200 !== $client->getStatusCode()) {
            throw new BinListLookupException(sprintf(
                'Unexpected status code %d received from remote server.',
                $client->getStatusCode()
            ));
        }

        $binData = json_decode($client->getResponseBody());
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BinListLookupException('Unable to deserialize response.');
        }

        if (false === isset($binData->country->alpha2)) {
            throw new BinListLookupException('Unable to detect country.');
        }

        return $binData->country->alpha2;
    }
}
