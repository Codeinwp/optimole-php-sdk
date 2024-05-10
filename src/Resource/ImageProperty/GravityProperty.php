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
use Optimole\Sdk\ValueObject\Position;

class GravityProperty implements PropertyInterface
{
    /**
     * Detects the most "interesting" section of the image and considers it as the center of the resulting image.
     */
    public const SMART = 'sm';

    /**
     * Sets the gravity to the focus point to a specific coordinate.
     */
    private const FOCUS_POINT = 'fp';

    /**
     * When using the focus point gravity, defines the coordinates of the resulting image on the X axis.
     */
    private float $focusPointX;

    /**
     * When using the focus point gravity, defines the coordinates of the resulting image on the Y axis.
     */
    private float $focusPointY;

    /**
     * The image gravity type.
     */
    private string $gravity;

    /**
     * Constructor.
     */
    public function __construct($gravity)
    {
        if (!is_string($gravity) && !is_array($gravity) && !$gravity instanceof Position) {
            throw new InvalidArgumentException('Image gravity must be a string, an array or an instance of Position.');
        } elseif (is_array($gravity) && !isset($gravity[0], $gravity[1])) {
            throw new InvalidArgumentException('Focus point image gravity must be an array with two elements.');
        }

        if (is_string($gravity) && self::SMART !== $gravity) {
            $gravity = new Position($gravity);
        }

        $this->focusPointX = is_array($gravity) ? (float) $gravity[0] : 0;
        $this->focusPointY = is_array($gravity) ? (float) $gravity[1] : 0;
        $this->gravity = is_array($gravity) ? self::FOCUS_POINT : (string) $gravity;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $gravity = sprintf('g:%s', $this->gravity);

        if (self::FOCUS_POINT === $this->gravity) {
            $gravity .= sprintf(':%s:%s', $this->focusPointX, $this->focusPointY);
        }

        return $gravity;
    }
}
