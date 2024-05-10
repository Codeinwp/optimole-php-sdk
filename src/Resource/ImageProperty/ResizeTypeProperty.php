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

class ResizeTypeProperty implements PropertyInterface
{
    /**
     * Resize the image by cropping it to fit a given size.
     */
    public const CROP = 'crop';

    /**
     * Resize the image while keeping aspect ratio to fill a given size and crops projecting parts.
     */
    public const FILL = 'fill';

    /**
     * Resize the image while keeping aspect ratio to fit a given size.
     */
    public const FIT = 'fit';

    /**
     * The allowed resize types.
     */
    private const ALLOWED_TYPES = [self::CROP, self::FILL, self::FIT];

    /**
     * The image resize type.
     */
    private string $type;

    /**
     * Constructor.
     */
    public function __construct(string $type)
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new InvalidArgumentException('Image resize type must be "crop", "fill" or "fit".');
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('rt:%s', $this->type);
    }
}
