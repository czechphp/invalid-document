<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument\Tests;

use Czechphp\InvalidDocument\Exception\ServerErrorException;
use Czechphp\InvalidDocument\InvalidDocument;
use Czechphp\InvalidDocument\InvalidDocumentInterface;
use Czechphp\InvalidDocument\Message\MessageInterface;
use Czechphp\InvalidDocument\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class InvalidDocumentTest extends TestCase
{
    public function testSuccess(): void
    {
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $parser = $this->createMock(ParserInterface::class);
        $client = $this->createMock(ClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $message = $this->createMock(MessageInterface::class);

        $requestFactory->expects($this->once())->method('createRequest')->willReturn($request);
        $client->expects($this->once())->method('sendRequest')->willReturn($response);
        $response->expects($this->once())->method('getBody')->willReturn($stream);
        $stream->expects($this->once())->method('getContents')->willReturn('');
        $parser->expects($this->once())->method('parse')->willReturn($message);

        $invalidDocument = new InvalidDocument($client, $requestFactory, $parser);

        $this->assertEquals($message, $invalidDocument->get(InvalidDocumentInterface::IDENTIFICATION_CARD, '123AB'));
    }

    public function testClientException(): void
    {
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $parser = $this->createMock(ParserInterface::class);
        $client = $this->createMock(ClientInterface::class);
        $exception = $this->createMock(ClientExceptionInterface::class);

        $requestFactory->expects($this->once())->method('createRequest')->willReturn($request);
        $client->expects($this->once())->method('sendRequest')->willThrowException($exception);

        $invalidDocument = new InvalidDocument($client, $requestFactory, $parser);

        $this->expectException(ServerErrorException::class);

        $invalidDocument->get(InvalidDocumentInterface::IDENTIFICATION_CARD, '123AB');
    }
}
