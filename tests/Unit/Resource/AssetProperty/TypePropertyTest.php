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

namespace Optimole\Sdk\Tests\Unit\Resource\AssetProperty;

use Optimole\Sdk\Resource\AssetProperty\TypeProperty;
use PHPUnit\Framework\TestCase;

class TypePropertyTest extends TestCase
{
    public function testLowerCasesType(): void
    {
        $this->assertSame('f:foo', (string) (new TypeProperty('Foo')));
    }
}
