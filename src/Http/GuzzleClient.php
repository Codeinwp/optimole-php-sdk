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

namespace Optimole\Sdk\Http;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Optimole;

class GuzzleClient implements ClientInterface
{
    /**
     * The Guzzle HTTP client.
     */
    private GuzzleClientInterface $client;

    /**
     * Constructor.
     */
    public function __construct(GuzzleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(string $method, string $url, $body = null, array $headers = []): ?array
    {
        try {
            $response = $this->client->send($this->createRequest($method, $url, $body, $headers), ['verify' => false]);
        } catch (GuzzleBadResponseException $exception) {
            throw new BadResponseException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (GuzzleException $exception) {
            throw new RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $body = (string) $response->getBody();

        if (empty($body)) {
            return null;
        }

        $body = (array) json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadResponseException(sprintf('Unable to decode JSON response: %s', json_last_error_msg()));
        }

        return $body;
    }

    /**
     * Create a request object.
     */
    private function createRequest(string $method, string $url, $body = null, array $headers = []): Request
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        if (null !== $body && !is_string($body)) {
            throw new InvalidArgumentException('"body" must be a string or an array');
        }

        $headers = array_merge($headers, [
            'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
        ]);
        $method = strtolower($method);

        return new Request($method, $url, $headers, $body);
    }
}
