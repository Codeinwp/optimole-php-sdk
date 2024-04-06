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

namespace Optimole\Sdk\Tests\Unit\Resource;

use Optimole\Sdk\Resource\Image;
use Optimole\Sdk\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testFormatAddsFormatProperty(): void
    {
        $image = (new Image('domain', 'source'))->format('webp');

        $this->assertSame('https://domain/f:webp/source', (string) $image);
    }

    public function testHeightAddsHeightProperty(): void
    {
        $image = (new Image('domain', 'source'))->height(100);

        $this->assertSame('https://domain/h:100/source', (string) $image);
    }

    public function testIgnoreAvifAddsIgnoreAvifProperty(): void
    {
        $image = (new Image('domain', 'source'))->ignoreAvif();

        $this->assertSame('https://domain/ig:avif/source', (string) $image);
    }

    public function testKeepCopyrightAddsKeepCopyrightProperty(): void
    {
        $image = (new Image('domain', 'source'))->keepCopyright();

        $this->assertSame('https://domain/keep_copyright:true/source', (string) $image);
    }

    public function testQualityAddsQualityProperty(): void
    {
        $image = (new Image('domain', 'source'))->quality(100);

        $this->assertSame('https://domain/q:100/source', (string) $image);
    }

    public function testResizeAddsEnlargePropertyWhenEnlargeIsTrue(): void
    {
        $image = (new Image('domain', 'source'))->resize('crop', Position::CENTER, true);

        $this->assertSame('https://domain/rt:crop/g:ce/el:1/source', (string) $image);
    }

    public function testResizeAddsResizeAndGravityProperties(): void
    {
        $image = (new Image('domain', 'source'))->resize('crop');

        $this->assertSame('https://domain/rt:crop/g:ce/source', (string) $image);
    }

    public function testStripMetadataAddsStripMetadataProperty(): void
    {
        $image = (new Image('domain', 'source'))->stripMetadata();

        $this->assertSame('https://domain/sm:1/source', (string) $image);
    }

    public function testWatermarkAddsWatermarkProperty(): void
    {
        $image = (new Image('domain', 'source'))->watermark(42, 0.5);

        $this->assertSame('https://domain/wm:42:0.5:ce/source', (string) $image);
    }

    public function testWidthAddsWidthProperty(): void
    {
        $image = (new Image('domain', 'source'))->width(100);

        $this->assertSame('https://domain/w:100/source', (string) $image);
    }
}
