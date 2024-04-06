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

use Optimole\Sdk\Resource\ImageProperty\WidthProperty;
use PHPUnit\Framework\TestCase;

class WidthPropertyTest extends TestCase
{
    public function testConstructorWithInvalidWidth(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image width must be "auto" or an integer.');

        new WidthProperty('foo');
    }

    public function testWithAuto(): void
    {
        $this->assertSame('w:auto', (string) (new WidthProperty('auto')));
    }

    public function testWithIntegerGreaterThanZero(): void
    {
        $this->assertSame('w:42', (string) (new WidthProperty(42)));
    }

    public function testWithIntegerLessThanZero(): void
    {
        $this->assertSame('w:0', (string) (new WidthProperty(-42)));
    }
}
