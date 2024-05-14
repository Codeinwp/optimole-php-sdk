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

use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Http\WordPressClient;
use Optimole\Sdk\Optimole;
use PHPUnit\Framework\TestCase;

class WordPressClientTest extends TestCase
{
    public function testSendRequestReturnsJsonDecodedBody()
    {
        $wordpressHttp = $this->createMock(WP_Http::class);
        $wordpressHttp->expects($this->once())
                      ->method('request')
                      ->with($this->identicalTo('https://example.com'), $this->identicalTo([
                          'method' => 'GET',
                          'headers' => [
                              'Content-Type' => 'application/json',
                              'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
                          ],
                          'body' => '{"foo":"bar"}',
                      ]))
                      ->willReturn(['body' => '{"bar":"foo"}', 'response' => ['code' => 200]]);

        $this->assertSame(['bar' => 'foo'], (new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']));
    }

    public function testSendRequestReturnsNullWithEmptyBody()
    {
        $wordpressHttp = $this->createMock(WP_Http::class);
        $wordpressHttp->expects($this->once())
                      ->method('request')
                      ->with($this->identicalTo('https://example.com'), $this->identicalTo([
                          'method' => 'GET',
                          'headers' => [
                              'Content-Type' => 'application/json',
                              'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
                          ],
                          'body' => '{"foo":"bar"}',
                      ]))
                      ->willReturn(['body' => '', 'response' => ['code' => 200]]);

        $this->assertNull((new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']));
    }

    public function testSendRequestWhenCannotDecodeJsonBody()
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('Unable to decode JSON response: State mismatch (invalid or malformed JSON)');

        $wordpressHttp = $this->createMock(WP_Http::class);
        $wordpressHttp->expects($this->once())
                      ->method('request')
                      ->with($this->identicalTo('https://example.com'), $this->identicalTo([
                          'method' => 'GET',
                          'headers' => [
                              'Content-Type' => 'application/json',
                              'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
                          ],
                          'body' => '{"foo":"bar"}',
                      ]))
                      ->willReturn(['body' => '[}', 'response' => ['code' => 200]]);

        (new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestWhenRequestDoesntReturn200StatusCode()
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('Response status code: 400');

        $wordpressHttp = $this->createMock(WP_Http::class);
        $wordpressHttp->expects($this->once())
                      ->method('request')
                      ->with($this->identicalTo('https://example.com'), $this->identicalTo([
                          'method' => 'GET',
                          'headers' => [
                              'Content-Type' => 'application/json',
                              'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
                          ],
                          'body' => '{"foo":"bar"}',
                      ]))
                      ->willReturn(['response' => ['code' => 400]]);

        (new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestWhenRequestReturnsWpErrorObject()
    {
        $this->expectException(RuntimeException::class);

        $wordpressHttp = $this->createMock(WP_Http::class);
        $wordpressHttp->expects($this->once())
                      ->method('request')
                      ->with($this->identicalTo('https://example.com'), $this->identicalTo([
                          'method' => 'GET',
                          'headers' => [
                              'Content-Type' => 'application/json',
                              'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
                          ],
                          'body' => '{"foo":"bar"}',
                      ]))
                      ->willReturn(new WP_Error('http_request_failed', 'An error occurred'));

        (new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', ['foo' => 'bar'], ['Content-Type' => 'application/json']);
    }

    public function testSendRequestWithBodyNotStringOrArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"body" must be a string or an array');

        $wordpressHttp = $this->createMock(WP_Http::class);

        (new WordPressClient($wordpressHttp))->sendRequest('GET', 'https://example.com', new stdClass(), ['Content-Type' => 'application/json']);
    }
}
