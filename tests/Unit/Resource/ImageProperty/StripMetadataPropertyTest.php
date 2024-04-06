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

use Optimole\Sdk\Resource\ImageProperty\StripMetadataProperty;
use PHPUnit\Framework\TestCase;

class StripMetadataPropertyTest extends TestCase
{
    public function testWithStripMetadataDisabled(): void
    {
        $this->assertSame('sm:0', (string) (new StripMetadataProperty(false)));
    }

    public function testWithStripMetadataEnabled(): void
    {
        $this->assertSame('sm:1', (string) (new StripMetadataProperty(true)));
    }
}
