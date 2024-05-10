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

namespace Optimole\Sdk\Tests\Unit\Resource\ImageProperty;

use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Resource\ImageProperty\WatermarkProperty;
use Optimole\Sdk\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class WatermarkPropertyTest extends TestCase
{
    public function testConstructorWithInvalidPositionString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "foo" is invalid.');

        new WatermarkProperty(1, 0.5, 'foo');
    }

    public function testConstructorWithInvalidPositionType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Watermark position must be a string or an instance of Position.');

        new WatermarkProperty(1, 0.5, 1);
    }

    public function testWithIdOpacityAndPosition(): void
    {
        $this->assertSame('wm:42:0.5:ce', (string) (new WatermarkProperty(42, 0.5, Position::CENTER)));
    }

    public function testWithIdOpacityPositionAndXOffset(): void
    {
        $this->assertSame('wm:42:0.5:ce:42', (string) (new WatermarkProperty(42, 0.5, Position::CENTER, 42)));
    }

    public function testWithIdOpacityPositionXOffsetAndYOffset(): void
    {
        $this->assertSame('wm:42:0.5:ce:42:24', (string) (new WatermarkProperty(42, 0.5, Position::CENTER, 42, 24)));
    }

    public function testWithIdOpacityPositionXOffsetYOffsetAndScale(): void
    {
        $this->assertSame('wm:42:0.5:ce:42:24:0.4', (string) (new WatermarkProperty(42, 0.5, Position::CENTER, 42, 24, 0.4)));
    }
}
