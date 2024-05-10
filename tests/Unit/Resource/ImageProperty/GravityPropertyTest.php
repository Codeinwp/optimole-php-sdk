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
use Optimole\Sdk\Resource\ImageProperty\GravityProperty;
use Optimole\Sdk\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class GravityPropertyTest extends TestCase
{
    public function testConstructorWithInvalidFocusPointArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Focus point image gravity must be an array with two elements.');

        new GravityProperty([]);
    }

    public function testConstructorWithInvalidPositionString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "foo" is invalid.');

        new GravityProperty('foo');
    }

    public function testConstructorWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image gravity must be a string, an array or an instance of Position.');

        new GravityProperty(true);
    }

    public function testWithFocusPointArray(): void
    {
        $this->assertSame('g:fp:42:24', (string) (new GravityProperty([42, 24])));
    }

    public function testWithPositionString(): void
    {
        $this->assertSame('g:ce', (string) (new GravityProperty(Position::CENTER)));
    }

    public function testWithSmartGravity(): void
    {
        $this->assertSame('g:sm', (string) (new GravityProperty(GravityProperty::SMART)));
    }
}
