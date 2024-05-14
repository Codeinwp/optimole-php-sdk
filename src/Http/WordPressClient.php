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

use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Optimole;

class WordPressClient implements ClientInterface
{
    /**
     * The WordPress HTTP client.
     */
    private \WP_Http $client;

    /**
     * Constructor.
     */
    public function __construct(\WP_Http $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(string $method, string $url, $body = null, array $headers = []): ?array
    {
        if (is_array($body)) {
            $body = json_encode($body);
        }

        if (null !== $body && !is_string($body)) {
            throw new InvalidArgumentException('"body" must be a string or an array');
        }

        $args = [
            'method' => $method,
            'headers' => array_merge($headers, [
                'User-Agent' => sprintf('optimole-sdk-php/%s', Optimole::VERSION),
            ]),
        ];

        if (null !== $body) {
            $args['body'] = $body;
        }

        $response = $this->client->request($url, $args);

        if ($response instanceof \WP_Error) {
            throw new RuntimeException((string) $response->get_error_message(), (int) $response->get_error_code());
        } elseif (200 !== $this->getResponseStatusCode($response)) {
            throw new BadResponseException(sprintf('Response status code: %s', $this->getResponseStatusCode($response)));
        }

        if (empty($response['body'])) {
            return null;
        }

        $body = (array) json_decode($response['body'], true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadResponseException(sprintf('Unable to decode JSON response: %s', json_last_error_msg()));
        }

        return $body;
    }

    /**
     * Get the status code from the given response.
     */
    private function getResponseStatusCode(array $response): ?int
    {
        return $response['response']['code'] ?? null;
    }
}
