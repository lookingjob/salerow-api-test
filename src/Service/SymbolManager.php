<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

/**
 * Symbols Manager class.
 */
class SymbolManager
{
    /**
     * @var string
     */
    private string $apiUrl = 'https://www.binance.com/bapi/composite/v1/public/marketing/symbol/list';

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function getFilteredList(float $marketCapLowerBound): ?array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl);
        if (200 !== $response->getStatusCode()) {
            return null;
        }

        $result = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        if (empty($result->data)) {
            return null;
        }

        return array_filter($result->data, static function($symbol) use ($marketCapLowerBound) {
            return $symbol->marketCap > $marketCapLowerBound;
        });
    }
}
