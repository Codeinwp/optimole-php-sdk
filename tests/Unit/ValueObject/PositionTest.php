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
    public function testConstructorWithInvalidPosition(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "foo" is invalid.');

        new Position('foo');
    }

    public function testToString(): void
    {
        $position = new Position(Position::CENTER);

        $this->assertSame(Position::CENTER, (string) $position);
    }
}
