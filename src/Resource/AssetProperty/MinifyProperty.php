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

class MinifyProperty implements PropertyInterface
{
    /**
     * Flag to determine if asset should be minified or not.
     */
    private bool $minify;

    /**
     * Constructor.
     */
    public function __construct(bool $minify = true)
    {
        $this->minify = $minify;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('m:%d', (int) $this->minify);
    }
}
