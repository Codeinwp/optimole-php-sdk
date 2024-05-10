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
use Optimole\Sdk\Resource\ImageProperty\ResizeTypeProperty;
use PHPUnit\Framework\TestCase;

class ResizeTypePropertyTest extends TestCase
{
    public function provideResizeTypes(): array
    {
        return [
            [ResizeTypeProperty::CROP],
            [ResizeTypeProperty::FILL],
            [ResizeTypeProperty::FIT],
        ];
    }

    public function testConstructorWithInvalidResizeType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image resize type must be "crop", "fill" or "fit".');

        new ResizeTypeProperty('foo');
    }

    /**
     * @dataProvider provideResizeTypes
     */
    public function testConstructorWithResizeType(string $resizeType): void
    {
        $this->assertSame(sprintf('rt:%s', $resizeType), (string) (new ResizeTypeProperty($resizeType)));
    }
}
