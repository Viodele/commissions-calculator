<?php declare(strict_types=1);

namespace App\Service;

final class SimpleHttpClient
{
    private static ?SimpleHttpClient $_instance = null;
    private ?string $responseBody =  null;
    private int $statusCode = 0;

    private function __construct() {}

    public static function create(): SimpleHttpClient
    {
        if (null === self::$_instance) {
            self::$_instance = new SimpleHttpClient();
        }

        return self::$_instance;
    }

    public function submit(string $method, string $url, ?string $body = null, array $headers = []): void
    {
        $this->statusCode = 0;

        $options = [
            'method' => $method,
            'ignore_errors' => true,
        ];

        if (false === empty($body)) {
            $options['content'] = $body;
        }

        if (false === empty($headers)) {
            $options['header'] = implode("\r\n", $headers);
        }

        $context = stream_context_create([
            'http' => $options,
        ]);

        $this->responseBody = file_get_contents($url, false, $context);
        if (true === (bool)preg_match('/HTTP\/\S*\s(\d+)/', $http_response_header[0], $matches)) {
            $this->statusCode = intval($matches[1]);
        }
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}

// "{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}"