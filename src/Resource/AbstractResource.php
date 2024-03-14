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

namespace Optimole\Sdk\Resource;

abstract class AbstractResource
{
    /**
     * The Optimole domain to use when generating the URL.
     */
    private string $domain;

    /**
     * The optimization properties used to optimize the resource.
     *
     * @var PropertyInterface[]
     */
    private array $properties;

    /**
     * The source of the resource being optimized.
     */
    private string $source;

    /**
     * Constructor.
     */
    public function __construct(string $domain, string $source, string $cacheBuster = '')
    {
        if (!filter_var($domain, FILTER_VALIDATE_DOMAIN)) {
            throw new \InvalidArgumentException('The domain must be a valid domain.');
        }

        $this->domain = $domain;
        $this->properties = [];
        $this->source = $source;

        if (!empty($cacheBuster)) {
            $this->properties[] = new CacheBusterProperty($cacheBuster);
        }
    }

    /**
     * Get the URL of the optimized resource.
     */
    public function __toString(): string
    {
        return $this->getUrl();
    }

    /**
     * Get the optimization properties used to optimize the resource.
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Get the source of the resource being optimized.
     */
    public function getSource(): string
    {
        return is_numeric($this->source) ? sprintf('id:%s', $this->source) : $this->source;
    }

    /**
     * Get the URL for the optimized resource.
     */
    public function getUrl(): string
    {
        return sprintf('https://%s/%s/%s', $this->domain, implode('/', $this->properties), ltrim($this->getSource(), '/'));
    }

    /**
     * Add a property to the optimization properties.
     */
    protected function addProperty(PropertyInterface $property): void
    {
        $this->properties[] = $property;
    }
}
