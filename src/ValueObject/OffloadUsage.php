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

namespace Optimole\Sdk\ValueObject;

final class OffloadUsage
{
    /**
     * The current number of offloaded images.
     */
    private int $current;

    /**
     * The maximum number of offloaded images allowed.
     */
    private int $limit;

    /**
     * Constructor.
     */
    public function __construct(int $current, int $limit)
    {
        $this->current = $current;
        $this->limit = $limit;
    }

    /**
     * Get the current number of offloaded images.
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * Get the maximum number of offloaded images allowed.
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
