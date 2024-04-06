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

use Optimole\Sdk\Resource\ImageProperty\KeepCopyrightProperty;
use PHPUnit\Framework\TestCase;

class KeepCopyrightPropertyTest extends TestCase
{
    public function testToString(): void
    {
        $this->assertSame('keep_copyright:true', (string) (new KeepCopyrightProperty()));
    }
}
