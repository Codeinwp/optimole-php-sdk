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

use Optimole\Sdk\Resource\Asset;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    public function testAddsCssTypeForCssSource(): void
    {
        $this->assertSame('https://domain/f:css/https://example.com/style.css', (string) (new Asset('domain', 'https://example.com/style.css')));
    }

    public function testAddsJsTypeForJsSource(): void
    {
        $this->assertSame('https://domain/f:js/https://example.com/script.js', (string) (new Asset('domain', 'https://example.com/script.js')));
    }

    public function testDoesntAddTypeForUnknownSource(): void
    {
        $this->assertSame('https://domain/https://example.com/foo.bar', (string) (new Asset('domain', 'https://example.com/foo.bar')));
    }

    public function testMinifyAddsMinifyProperty(): void
    {
        $asset = (new Asset('domain', 'https://example.com/foo.bar'))->minify();

        $this->assertSame('https://domain/m:1/https://example.com/foo.bar', (string) $asset);
    }

    public function testQualityAddsQualityProperty(): void
    {
        $asset = (new Asset('domain', 'https://example.com/foo.bar'))->quality();

        $this->assertSame('https://domain/q:mauto/https://example.com/foo.bar', (string) $asset);
    }
}
