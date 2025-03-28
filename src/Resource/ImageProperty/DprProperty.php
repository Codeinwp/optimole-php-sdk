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

namespace Optimole\Sdk\Resource\ImageProperty;

use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Resource\PropertyInterface;

class DprProperty implements PropertyInterface
{
    /**
     * The dpr of the image.
     */
    private $dpr;

    /**
     * Constructor.
     */
    public function __construct($dpr = 1)
    {
        if (!is_int($dpr) || $dpr < 1) {
            throw new InvalidArgumentException('Image dpr must be an integer greater or equal than 1.');
        }
        $dpr = max(1, min(5, $dpr));

        $this->dpr = $dpr;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('dpr:%s', $this->dpr);
    }
}
