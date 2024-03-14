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

class StripMetadataProperty implements PropertyInterface
{
    /**
     * Flag to determine if metadata should be stripped or not.
     */
    private bool $stripMetadata;

    /**
     * Constructor.
     */
    public function __construct(bool $stripMetadata = true)
    {
        $this->stripMetadata = $stripMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('sm:%d', (int) $this->stripMetadata);
    }
}
