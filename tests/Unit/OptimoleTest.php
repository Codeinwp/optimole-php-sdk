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

namespace Optimole\Sdk\Tests\Unit;

use Optimole\Sdk\Exception\BadMethodCallException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Offload\Manager;
use Optimole\Sdk\Optimole;
use Optimole\Sdk\Resource\Asset;
use Optimole\Sdk\Resource\Image;
use PHPUnit\Framework\TestCase;

class OptimoleTest extends TestCase
{
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Optimole::class);

        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);

        $instanceProperty->setValue(null, null);
    }

    public function testAssetDoesntUseCacheBusterOptionIfCacheBusterGiven(): void
    {
        Optimole::init('key', ['cache_buster' => 'foo']);

        $this->assertSame('https://key.i.optimole.com/cb:bar/https://example.com/image.jpg', (string) Optimole::asset('https://example.com/image.jpg', 'bar'));
    }

    public function testAssetReturnsAssetObject(): void
    {
        Optimole::init('key');

        $this->assertInstanceOf(Asset::class, Optimole::asset('https://example.com/style.css'));
    }

    public function testAssetUsesBaseDomainOption(): void
    {
        Optimole::init('key', ['base_domain' => 'foo']);

        $this->assertSame('https://key.foo/https://example.com/image.jpg', (string) Optimole::asset('https://example.com/image.jpg'));
    }

    public function testAssetUsesCacheBusterOptionIfNoCacheBusterGiven(): void
    {
        Optimole::init('key', ['cache_buster' => 'foo']);

        $this->assertSame('https://key.i.optimole.com/cb:foo/https://example.com/image.jpg', (string) Optimole::asset('https://example.com/image.jpg'));
    }

    public function testAssetUsesDomainOption(): void
    {
        Optimole::init('key', ['domain' => 'foo']);

        $this->assertSame('https://foo/https://example.com/image.jpg', (string) Optimole::asset('https://example.com/image.jpg'));
    }

    public function testImageDoesntUseCacheBusterOptionIfCacheBusterGiven(): void
    {
        Optimole::init('key', ['cache_buster' => 'foo']);

        $this->assertSame('https://key.i.optimole.com/cb:bar/https://example.com/image.jpg', (string) Optimole::image('https://example.com/image.jpg', 'bar'));
    }

    public function testImageReturnsImageObject(): void
    {
        Optimole::init('key');

        $this->assertInstanceOf(Image::class, Optimole::image('https://example.com/image.jpg'));
    }

    public function testImageUsesBaseDomainOption(): void
    {
        Optimole::init('key', ['base_domain' => 'foo']);

        $this->assertSame('https://key.foo/https://example.com/image.jpg', (string) Optimole::image('https://example.com/image.jpg'));
    }

    public function testImageUsesCacheBusterOptionIfNoCacheBusterGiven(): void
    {
        Optimole::init('key', ['cache_buster' => 'foo']);

        $this->assertSame('https://key.i.optimole.com/cb:foo/https://example.com/image.jpg', (string) Optimole::image('https://example.com/image.jpg'));
    }

    public function testImageUsesDomainOption(): void
    {
        Optimole::init('key', ['domain' => 'foo']);

        $this->assertSame('https://foo/https://example.com/image.jpg', (string) Optimole::image('https://example.com/image.jpg'));
    }

    public function testInitializedWhenSdkIsInitialized(): void
    {
        Optimole::init('key');

        $this->assertTrue(Optimole::initialized());
    }

    public function testInitializedWhenSdkIsNotInitialized(): void
    {
        $this->assertFalse(Optimole::initialized());
    }

    public function testOffloadReturnsOffloadManagerObject(): void
    {
        Optimole::init('key');

        $this->assertInstanceOf(Manager::class, Optimole::offload());
    }

    public function testOffloadUsesDashboardApiOption(): void
    {
        Optimole::init('key', [
            'dashboard_api_url' => 'dashboard_api_url',
        ]);

        $manager = Optimole::offload();

        $optionsProperty = (new \ReflectionObject($manager))->getProperty('options');
        $optionsProperty->setAccessible(true);

        $options = $optionsProperty->getValue($manager);

        $this->assertArrayHasKey('dashboard_api_url', $options);
        $this->assertSame('dashboard_api_url', $options['dashboard_api_url']);
    }

    public function testOffloadUsesUploadApiOption(): void
    {
        Optimole::init('key', [
            'upload_api_url' => 'upload_api_url',
        ]);

        $manager = Optimole::offload();

        $optionsProperty = (new \ReflectionObject($manager))->getProperty('options');
        $optionsProperty->setAccessible(true);

        $options = $optionsProperty->getValue($manager);

        $this->assertArrayHasKey('upload_api_url', $options);
        $this->assertSame('upload_api_url', $options['upload_api_url']);
    }

    public function testThrowsExceptionIfMethodDoesNotExist(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('No factory method for "foo" exists.');

        Optimole::init('key');
        Optimole::foo('https://example.com/image.jpg');
    }

    public function testThrowsExceptionIfNotInitialized(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Please initialize the Optimole SDK first.');

        Optimole::asset('https://example.com/image.jpg');
    }
}
