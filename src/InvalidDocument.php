<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument;

use Czechphp\InvalidDocument\Exception\InvalidArgumentException;
use Czechphp\InvalidDocument\Exception\ServerErrorException;
use Czechphp\InvalidDocument\Message\MessageInterface;
use Czechphp\InvalidDocument\Parser\XmlParser;
use Czechphp\InvalidDocument\Parser\ParserInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use function http_build_query;
use function sprintf;

final class InvalidDocument
{
    public const URI = 'https://aplikace.mvcr.cz/neplatne-doklady/Doklady.aspx';

    /**
     * Občanský průkaz
     */
    public const IDENTIFICATION_CARD = 0;

    /**
     * Centrálně vydávaný cestovní pas
     */
    public const CENTRALLY_ISSUED_PASSPORT = 4;

    /**
     * Cestovní pas vydaný okresním úřadem
     */
    public const REGIONALLY_ISSUED_PASSPORT = 5;

    /**
     * Zbrojní průkaz
     */
    public const GUN_LICENSE = 6;

    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private ParserInterface $responseParser;
    private string $uri;

    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory, ParserInterface $responseParser = null, string $uri = self::URI)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->responseParser = $responseParser ?: new XmlParser();
        $this->uri = $uri;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ServerErrorException
     */
    public function get(int $documentType, string $number): MessageInterface
    {
        $request = $this->requestFactory->createRequest('GET', $this->getUri($documentType, $number));

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ServerErrorException('Failed to obtain response', 0, $e);
        }

        return $this->responseParser->parse($response->getBody()->getContents());
    }

    private function getUri(int $documentType, string $number): string
    {
        return sprintf('%s?%s', $this->uri, http_build_query([
            'dotaz' => $number,
            'doklad' => $documentType,
        ]));
    }
}
