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
        $this->domain = $domain;
        $this->properties = [];
        $this->source = $source;

        if (!empty($cacheBuster)) {
            $this->properties[] = new CacheBusterProperty($cacheBuster);
        }
    }

    /**
     * Convert the optimized resource to its string representation.
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
        $url = sprintf('https://%s', trim($this->domain, '/'));

        if (!empty($this->properties)) {
            $url .= sprintf('/%s', implode('/', $this->properties));
        }

        return sprintf('%s/%s', $url, ltrim($this->getSource(), '/'));
    }

    /**
     * Add a property to the optimization properties.
     */
    protected function addProperty(PropertyInterface $property): void
    {
        $this->properties[] = $property;
    }
}
