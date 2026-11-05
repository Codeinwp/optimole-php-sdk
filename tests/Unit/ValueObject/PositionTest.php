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

namespace Optimole\Sdk\Tests\Unit\ValueObject;

use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    /**
     * The gravity value the CDN gives each position (imgproxy meanings: "noea" is
     * north-east, the top right corner; "nowe" is north-west, the top left corner).
     */
    public function provideCdnGravityValues(): array
    {
        return [
            'center' => [Position::CENTER, 'ce'],
            'east (right edge)' => [Position::EAST, 'ea'],
            'north (top edge)' => [Position::NORTH, 'no'],
            'north east (top right corner)' => [Position::NORTH_EAST, 'noea'],
            'north west (top left corner)' => [Position::NORTH_WEST, 'nowe'],
            'south (bottom edge)' => [Position::SOUTH, 'so'],
            'south east (bottom right corner)' => [Position::SOUTH_EAST, 'soea'],
            'south west (bottom left corner)' => [Position::SOUTH_WEST, 'sowe'],
            'west (left edge)' => [Position::WEST, 'we'],
        ];
    }

    public function testConstructorWithInvalidPosition(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "foo" is invalid.');

        new Position('foo');
    }

    public function testEveryPositionIsCovered(): void
    {
        $expected = array_column($this->provideCdnGravityValues(), 1);
        $actual = array_values((new \ReflectionClass(Position::class))->getConstants());

        sort($expected);
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider provideCdnGravityValues
     */
    public function testPositionMatchesCdnGravity(string $position, string $expected): void
    {
        $this->assertSame($expected, $position);
        $this->assertSame($expected, (string) new Position($position));
    }

    public function testToString(): void
    {
        $position = new Position(Position::CENTER);

        $this->assertSame(Position::CENTER, (string) $position);
    }
}
