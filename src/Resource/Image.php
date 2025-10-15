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

namespace Optimole\Sdk\Resource;

use Optimole\Sdk\ValueObject\Position;

class Image extends AbstractResource
{
    /**
     * Set the dpr of the optimized image.
     */
    public function dpr($dpr = 1): self
    {
        $this->addProperty(new ImageProperty\DprProperty($dpr));

        return $this;
    }

    /**
     * Convert the optimized image to the given format.
     */
    public function format(string $format): self
    {
        $this->addProperty(new ImageProperty\FormatProperty($format));

        return $this;
    }

    /**
     * Set the height of the optimized image.
     */
    public function height($height): self
    {
        $this->addProperty(new ImageProperty\HeightProperty($height));

        return $this;
    }

    /**
     * Ignore the AVIF format when optimizing the image.
     */
    public function ignoreAvif(): self
    {
        $this->addProperty(new ImageProperty\IgnoreAvifProperty());

        return $this;
    }

    /**
     * Keep the original image copyright in the optimized image.
     */
    public function keepCopyright(): self
    {
        $this->addProperty(new ImageProperty\KeepCopyrightProperty());

        return $this;
    }

    /**
     * Set the quality of the optimized image.
     */
    public function quality($quality = 'mauto'): self
    {
        $this->addProperty(new ImageProperty\QualityProperty($quality));

        return $this;
    }

    /**
     * Resize the optimized image.
     */
    public function resize(string $type, $gravity = Position::CENTER, bool $enlarge = false): self
    {
        $this->addProperty(new ImageProperty\ResizeTypeProperty($type));
        $this->addProperty(new ImageProperty\GravityProperty($gravity));

        if ($enlarge) {
            $this->addProperty(new ImageProperty\EnlargeProperty());
        }

        return $this;
    }

    /**
     * Set the smart focus gravity of the optimized image.
     */
    public function smartFocus()
    {
        $this->addProperty(new ImageProperty\GravityProperty('sm'));

        return $this;
    }

    /**
     * Whether to strip the original image metadata from the optimized image.
     */
    public function stripMetadata(bool $stripMetadata = true): self
    {
        $this->addProperty(new ImageProperty\StripMetadataProperty($stripMetadata));

        return $this;
    }

    /**
     * Apply a watermark to the optimized image.
     */
    public function watermark(int $id, float $opacity, $position = Position::CENTER, float $offsetX = 0, float $offsetY = 0, float $scale = 0): self
    {
        $this->addProperty(new ImageProperty\WatermarkProperty($id, $opacity, $position, $offsetX, $offsetY, $scale));

        return $this;
    }

    /**
     * Set the width of the optimized image.
     */
    public function width($width): self
    {
        $this->addProperty(new ImageProperty\WidthProperty($width));

        return $this;
    }
}
