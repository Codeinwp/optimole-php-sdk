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

use Optimole\Sdk\Resource\AbstractResource;
use PHPUnit\Framework\TestCase;

class AbstractResourceTest extends TestCase
{
    public function testConstructorWithCacheBuster(): void
    {
        $resource = $this->getMockForAbstractClass(AbstractResource::class, ['domain', 'source', 'foo']);

        $this->assertSame('https://domain/cb:foo/source', (string) $resource);
    }

    public function testConstructorWithNonNumericSource(): void
    {
        $resource = $this->getMockForAbstractClass(AbstractResource::class, ['domain', 'source']);

        $this->assertSame('https://domain/source', (string) $resource);
    }

    public function testConstructorWithNumericSource(): void
    {
        $resource = $this->getMockForAbstractClass(AbstractResource::class, ['domain', '42']);

        $this->assertSame('https://domain/id:42', (string) $resource);
    }
}
