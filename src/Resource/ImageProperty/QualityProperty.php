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

class QualityProperty implements PropertyInterface
{
    /**
     * The allowed automated image quality options.
     */
    private const ALLOWED_QUALITY = ['auto', 'eco', 'mauto'];

    /**
     * The quality of the image.
     */
    private $quality;

    /**
     * Constructor.
     */
    public function __construct($quality = 'mauto')
    {
        if (!is_string($quality) && !is_int($quality)) {
            throw new InvalidArgumentException('Image quality must be a string or an integer.');
        } elseif (is_string($quality) && !in_array($quality, self::ALLOWED_QUALITY)) {
            throw new InvalidArgumentException('Image quality must be "auto", "eco" or "mauto".');
        } elseif (is_int($quality)) {
            $quality = max(0, min(100, $quality));
        }

        $this->quality = $quality;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('q:%s', $this->quality);
    }
}
