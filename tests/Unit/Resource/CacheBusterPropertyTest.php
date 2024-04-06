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

namespace Optimole\Sdk\Tests\Unit\Resource;

use Optimole\Sdk\Resource\CacheBusterProperty;
use PHPUnit\Framework\TestCase;

class CacheBusterPropertyTest extends TestCase
{
    public function testToString(): void
    {
        $this->assertSame('cb:Foo', (string) (new CacheBusterProperty('Foo')));
    }
}
