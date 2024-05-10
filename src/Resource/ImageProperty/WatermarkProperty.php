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

class WatermarkProperty implements PropertyInterface
{
    /**
     * The watermark ID in Optimole.
     */
    private int $id;

    /**
     * The horizontal offset of the watermark.
     */
    private float $offsetX;

    /**
     * The vertical offset of the watermark.
     */
    private float $offsetY;

    /**
     * The opacity of the watermark.
     */
    private float $opacity;

    /**
     * The position of the watermark.
     */
    private Position $position;

    /**
     * Defines the watermark size relative to the resultant image size.
     */
    private float $scale;

    /**
     * Constructor.
     */
    public function __construct(int $id, float $opacity, $position, float $offsetX = 0, float $offsetY = 0, float $scale = 0)
    {
        if (!is_string($position) && !$position instanceof Position) {
            throw new InvalidArgumentException('Watermark position must be a string or an instance of Position.');
        } elseif (is_string($position)) {
            $position = new Position($position);
        }

        $this->id = $id;
        $this->offsetX = $offsetX;
        $this->offsetY = $offsetY;
        $this->opacity = $opacity;
        $this->position = $position;
        $this->scale = $scale;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $watermark = sprintf('wm:%s:%s:%s', $this->id, $this->opacity, $this->position);

        if (empty($this->offsetX)) {
            return $watermark;
        }

        $watermark .= sprintf(':%s', $this->offsetX);

        if (empty($this->offsetY)) {
            return $watermark;
        }

        $watermark .= sprintf(':%s', $this->offsetY);

        if (empty($this->scale)) {
            return $watermark;
        }

        $watermark .= sprintf(':%s', $this->scale);

        return $watermark;
    }
}
