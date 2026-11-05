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
    /**
     * The resize types the CDN accepts.
     */
    public function provideResizeTypes(): array
    {
        return [
            'fit' => [ResizeTypeProperty::FIT, 'rt:fit'],
            'fill' => [ResizeTypeProperty::FILL, 'rt:fill'],
            'fill-down' => [ResizeTypeProperty::FILL_DOWN, 'rt:fill-down'],
            'force' => [ResizeTypeProperty::FORCE, 'rt:force'],
            'auto' => [ResizeTypeProperty::AUTO, 'rt:auto'],
        ];
    }

    public function testConstructorWithInvalidResizeType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image resize type must be "fit", "fill", "fill-down", "force" or "auto".');

        new ResizeTypeProperty('foo');
    }

    /**
     * @dataProvider provideResizeTypes
     */
    public function testConstructorWithResizeType(string $resizeType, string $expected): void
    {
        $this->assertSame($expected, (string) (new ResizeTypeProperty($resizeType)));
    }

    public function testCropResizeTypeIsSentAsFill(): void
    {
        $this->assertSame('rt:fill', (string) (new ResizeTypeProperty(ResizeTypeProperty::CROP)));
    }

    public function testCropResizeTypeTriggersDeprecation(): void
    {
        $deprecations = [];

        set_error_handler(static function (int $level, string $message) use (&$deprecations): bool {
            if (E_USER_DEPRECATED !== $level) {
                return false;
            }

            $deprecations[] = $message;

            return true;
        });

        try {
            new ResizeTypeProperty('crop');
        } finally {
            restore_error_handler();
        }

        $this->assertSame(['The "crop" image resize type is deprecated and is sent as "fill". Use "fill" instead.'], $deprecations);
    }
}
