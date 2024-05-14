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

use Optimole\Sdk\ValueObject\OffloadUsage;
use PHPUnit\Framework\TestCase;

class OffloadUsageTest extends TestCase
{
    public function testGetCurrent()
    {
        $this->assertSame(10, (new OffloadUsage(10, 100))->getCurrent());
    }

    public function testGetLimit()
    {
        $this->assertSame(100, (new OffloadUsage(10, 100))->getLimit());
    }
}
