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

class FormatProperty implements PropertyInterface
{
    /**
     * The image format to convert to.
     */
    private string $format;

    /**
     * Constructor.
     */
    public function __construct(string $format)
    {
        $this->format = strtolower($format);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('f:%s', $this->format);
    }
}
