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

use Optimole\Sdk\Resource\AssetProperty\MinifyProperty;
use PHPUnit\Framework\TestCase;

class MinifyPropertyTest extends TestCase
{
    public function testWithMinifyDisabled(): void
    {
        $this->assertSame('m:0', (string) (new MinifyProperty(false)));
    }

    public function testWithMinifyEnabled(): void
    {
        $this->assertSame('m:1', (string) (new MinifyProperty(true)));
    }
}
