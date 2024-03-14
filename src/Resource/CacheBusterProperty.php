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

namespace Optimole\Sdk\Resource;

class CacheBusterProperty implements PropertyInterface
{
    /**
     * The cache buster token.
     */
    private string $token;

    /**
     * Constructor.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('cb:%s', $this->token);
    }
}
