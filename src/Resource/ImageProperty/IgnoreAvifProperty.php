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

class IgnoreAvifProperty implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return 'ig:avif';
    }
}
