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

interface ClientInterface
{
    /**
     * Sends an HTTP request and returns the JSON decoded body.
     */
    public function sendRequest(string $method, string $url, $body = null, array $headers = []): ?array;
}
