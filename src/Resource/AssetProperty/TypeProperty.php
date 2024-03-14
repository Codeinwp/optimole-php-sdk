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

namespace Optimole\Sdk\Resource\AssetProperty;

use Optimole\Sdk\Resource\PropertyInterface;

class TypeProperty implements PropertyInterface
{
    /**
     * The type of the asset.
     */
    private string $type;

    /**
     * Constructor.
     */
    public function __construct(string $type)
    {
        $this->type = strtolower($type);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return 'f:'.$this->type;
    }
}
