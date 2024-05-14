<?php

declare(strict_types=1);

/*
 * This file is part of Optimole PHP SDK.
 *
 * (c) Optimole Team <friends@optimole.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Optimole\Sdk\Tests\Unit\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Http\GuzzleClient;
use Optimole\Sdk\Optimole;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GuzzleClientTest extends TestCase
{
    public function testSendRequestConvertsGuzzleBadResponseException()
    {
        $this->expectException(BadResponseException::class);

        $guzzle = $this->createMock(ClientInterface::class);

        $guzzle->expects($this->once())
               ->method('send')
               ->willThrowException($this->createMock(GuzzleBadResponseException::class));

        (new GuzzleClient($guzzle))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestConvertsGuzzleException()
    {
        $this->expectException(RuntimeException::class);

        $guzzle = $this->createMock(ClientInterface::class);

        $guzzle->expects($this->once())
               ->method('send')
               ->willThrowException($this->createMock(GuzzleException::class));

        (new GuzzleClient($guzzle))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestIsSuccessful()
    {
        $guzzle = $this->createMock(ClientInterface::class);

        $guzzle->expects($this->once())
               ->method('send')
               ->willReturnCallback(function ($request, $options) {
                   $this->assertInstanceOf(Request::class, $request);

                   $this->assertSame('GET', $request->getMethod());
                   $this->assertSame('https://example.com', (string) $request->getUri());
                   $this->assertSame('{"foo":"bar"}', (string) $request->getBody());
                   $this->assertSame(['Host' => ['example.com'], 'Content-Type' => ['application/json'], 'User-Agent' => [sprintf('optimole-sdk-php/%s', Optimole::VERSION)]], $request->getHeaders());
                   $this->assertSame(['verify' => false], $options);

                   return $this->createMock(ResponseInterface::class);
               });

        (new GuzzleClient($guzzle))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestWithBodyNotStringOrArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"body" must be a string or an array');

        $guzzle = $this->createMock(ClientInterface::class);

        (new GuzzleClient($guzzle))->sendRequest('GET', 'https://example.com', new \stdClass(), ['Content-Type' => 'application/json']);
    }
}
