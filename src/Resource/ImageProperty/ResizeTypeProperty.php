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
     * Use "fill" when the source and the requested size have the same orientation (portrait or landscape), "fit" otherwise.
     */
    public const AUTO = 'auto';

    /**
     * Sent as "fill": the CDN has no "crop" resize type and answers it with a redirect to the original image.
     *
     * @deprecated use ResizeTypeProperty::FILL instead
     */
    public const CROP = 'crop';

    /**
     * Resize the image while keeping aspect ratio to fill a given size and crops projecting parts.
     */
    public const FILL = 'fill';

    /**
     * Same as "fill", but if the resized image is smaller than the requested size, it is cropped to keep the requested aspect ratio.
     */
    public const FILL_DOWN = 'fill-down';

    /**
     * Resize the image while keeping aspect ratio to fit a given size.
     */
    public const FIT = 'fit';

    /**
     * Resize the image to the given size without keeping the aspect ratio.
     */
    public const FORCE = 'force';

    /**
     * The resize types the CDN accepts.
     */
    private const ALLOWED_TYPES = [self::FIT, self::FILL, self::FILL_DOWN, self::FORCE, self::AUTO];

    /**
     * The image resize type.
     */
    private string $type;

    /**
     * Constructor.
     */
    public function __construct(string $type)
    {
        if (self::CROP === $type) {
            @trigger_error('The "crop" image resize type is deprecated and is sent as "fill". Use "fill" instead.', E_USER_DEPRECATED);

            $type = self::FILL;
        }

        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new InvalidArgumentException('Image resize type must be "fit", "fill", "fill-down", "force" or "auto".');
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
