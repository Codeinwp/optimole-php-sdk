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

use Optimole\Sdk\Resource\ImageProperty\FormatProperty;
use PHPUnit\Framework\TestCase;

class FormatPropertyTest extends TestCase
{
    public function testLowerCasesType(): void
    {
        $this->assertSame('f:foo', (string) (new FormatProperty('Foo')));
    }
}
