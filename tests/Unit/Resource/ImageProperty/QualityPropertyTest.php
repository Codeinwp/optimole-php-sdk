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

use Optimole\Sdk\Resource\ImageProperty\QualityProperty;
use PHPUnit\Framework\TestCase;

class QualityPropertyTest extends TestCase
{
    public function provideQualityStrings(): array
    {
        return [
            ['auto'],
            ['eco'],
            ['mauto'],
        ];
    }

    public function testConstructorWithInvalidQualityString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image quality must be "auto", "eco" or "mauto".');

        new QualityProperty('foo');
    }

    public function testConstructorWithInvalidQualityType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image quality must be a string or an integer.');

        new QualityProperty([]);
    }

    /**
     * @dataProvider provideQualityStrings
     */
    public function testConstructorWithQualityString(string $quality): void
    {
        $this->assertSame(sprintf('q:%s', $quality), (string) (new QualityProperty($quality)));
    }

    public function testWithIntegerQualityBelow0(): void
    {
        $this->assertSame('q:0', (string) (new QualityProperty(-42)));
    }

    public function testWithIntegerQualityBetween0And100(): void
    {
        $this->assertSame('q:42', (string) (new QualityProperty(42)));
    }

    public function testWithIntegerQualityOver100(): void
    {
        $this->assertSame('q:100', (string) (new QualityProperty(4242)));
    }
}
