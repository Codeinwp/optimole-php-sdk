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

class WidthProperty implements PropertyInterface
{
    /**
     * The width of the image.
     */
    private $width;

    /**
     * Constructor.
     */
    public function __construct($width)
    {
        if ('auto' !== $width && !is_int($width)) {
            throw new InvalidArgumentException('Image width must be "auto" or an integer.');
        }

        $this->width = is_int($width) ? max(0, $width) : $width;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('w:%s', $this->width);
    }
}
