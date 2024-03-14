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

use Optimole\Sdk\Resource\PropertyInterface;

class HeightProperty implements PropertyInterface
{
    /**
     * The height of the image.
     */
    private $height;

    /**
     * Constructor.
     */
    public function __construct($height)
    {
        if ('auto' !== $height && !is_int($height)) {
            throw new \InvalidArgumentException('Image height must be "auto" or an integer.');
        } elseif (is_int($height) && $height < 0) {
            $height = 0;
        }

        $this->height = $height;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('h:%s', $this->height);
    }
}
