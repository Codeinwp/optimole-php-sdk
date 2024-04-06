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

use Optimole\Sdk\Resource\ImageProperty\HeightProperty;
use PHPUnit\Framework\TestCase;

class HeightPropertyTest extends TestCase
{
    public function testConstructorWithInvalidHeight(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image height must be "auto" or an integer.');

        new HeightProperty('foo');
    }

    public function testWithAuto(): void
    {
        $this->assertSame('h:auto', (string) (new HeightProperty('auto')));
    }

    public function testWithIntegerGreaterThanZero(): void
    {
        $this->assertSame('h:42', (string) (new HeightProperty(42)));
    }

    public function testWithIntegerLessThanZero(): void
    {
        $this->assertSame('h:0', (string) (new HeightProperty(-42)));
    }
}
