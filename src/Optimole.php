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

namespace Optimole\Sdk;

use Optimole\Sdk\Resource\Asset;
use Optimole\Sdk\Resource\Image;

/**
 * @method static Asset asset(string $assetUrl, string $cacheBuster = '')
 * @method static Image image(string $imageUrl, string $cacheBuster = '')
 */
final class Optimole
{
    /**
     * The Optimole SDK factory.
     */
    private static ?self $instance;

    /**
     * The Optimole API key.
     */
    private string $key;

    /**
     * The Optimole SDK global options.
     */
    private array $options;

    /**
     * Constructor.
     */
    private function __construct(string $key, array $options = [])
    {
        $this->key = $key;
        $this->options = $options;
    }

    /**
     * SDK factory method.
     */
    public static function __callStatic($name, $arguments)
    {
        $method = sprintf('create%s', ucfirst($name));

        if (!self::$instance instanceof self) {
            throw new \RuntimeException('Please initialize the Optimole SDK first.');
        } elseif (!method_exists(self::class, $method)) {
            throw new \BadMethodCallException(sprintf('No factory method for "%s" exists.', $name));
        }

        return self::$instance->$method(...$arguments);
    }

    /**
     * Initialize the Optimole SDK.
     */
    public static function init(string $key, array $options = []): void
    {
        $key = trim(strtolower($key));
        $options = array_merge([
            'base_domain' => 'i.optimole.com',
            'cache_buster' => '',
        ], $options);

        self::$instance = new self($key, $options);
    }

    /**
     * Create an asset resource.
     */
    private function createAsset(string $assetUrl, string $cacheBuster = ''): Asset
    {
        return new Asset($this->getDomain(), $assetUrl, $cacheBuster ?: $this->options['cache_buster']);
    }

    /**
     * Create an image resource.
     */
    private function createImage(string $imageUrl, string $cacheBuster = ''): Image
    {
        return new Image($this->getDomain(), $imageUrl, $cacheBuster ?: $this->options['cache_buster']);
    }

    /**
     * Get the Optimole domain to use.
     */
    private function getDomain(): string
    {
        return $this->options['domain'] ?? sprintf('%s.%s', $this->key, $this->options['base_domain']);
    }
}
