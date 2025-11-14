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

use GuzzleHttp\Client;
use Optimole\Sdk\Exception\BadMethodCallException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Http\ClientInterface;
use Optimole\Sdk\Http\GuzzleClient;
use Optimole\Sdk\Http\WordPressClient;
use Optimole\Sdk\Offload\Manager;
use Optimole\Sdk\Resource\Asset;
use Optimole\Sdk\Resource\Image;

/**
 * @method static Asset   asset(string $assetUrl, string $cacheBuster = '')
 * @method static Image   image(string $imageUrl, string $cacheBuster = '')
 * @method static Manager offload()
 */
final class Optimole
{
    /**
     * The Optimole SDK version.
     */
    public const VERSION = '1.2.4';

    /**
     * The Optimole dashboard API URL.
     */
    private const DASHBOARD_API_URL = 'https://dashboard.optimole.com/api';

    /**
     * The Optimole upload API URL.
     */
    private const UPLOAD_API_URL = 'https://generateurls-prod.i.optimole.com/upload';

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

        if (!self::initialized()) {
            throw new RuntimeException('Please initialize the Optimole SDK first.');
        } elseif (!method_exists(self::class, $method)) {
            throw new BadMethodCallException(sprintf('No factory method for "%s" exists.', $name));
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
            'dashboard_api_key' => '',
            'dashboard_api_url' => self::DASHBOARD_API_URL,
            'upload_api_url' => self::UPLOAD_API_URL,
        ], $options);

        self::$instance = new self($key, $options);
    }

    /**
     * Check if the SDK has been initialized.
     */
    public static function initialized(): bool
    {
        return isset(self::$instance);
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
     * Create an instance of offload manager.
     */
    private function createOffload(array $options = []): Manager
    {
        return new Manager($this->getHttpClient(), array_merge($this->options, $options));
    }

    /**
     * Get the Optimole domain to use.
     */
    private function getDomain(): string
    {
        return $this->options['domain'] ?? sprintf('%s.%s', $this->key, $this->options['base_domain']);
    }

    /**
     * Get the HTTP client available in the environment.
     */
    private function getHttpClient(): ClientInterface
    {
        if (class_exists(Client::class)) {
            return new GuzzleClient(new Client());
        } elseif (function_exists('_wp_http_get_object')) {
            return new WordPressClient(_wp_http_get_object());
        }

        throw new RuntimeException('Unable to find a suitable HTTP client for this environment');
    }
}
