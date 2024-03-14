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

class Asset extends AbstractResource
{
    /**
     * Constructor.
     */
    public function __construct(string $domain, string $source, string $cacheBuster = '')
    {
        parent::__construct($domain, $source, $cacheBuster);

        $source = strtolower($source);

        if (str_ends_with($source, '.css')) {
            $this->addProperty(new AssetProperty\TypeProperty('css'));
        } elseif (str_ends_with($source, '.js')) {
            $this->addProperty(new AssetProperty\TypeProperty('js'));
        }
    }

    /**
     * Whether to minify the asset or not.
     */
    public function minify(bool $minify = true): self
    {
        $this->addProperty(new AssetProperty\MinifyProperty($minify));

        return $this;
    }

    /**
     * Set the quality of the images linked inside the asset.
     */
    public function quality($quality = 'mauto'): self
    {
        $this->addProperty(new ImageProperty\QualityProperty($quality));

        return $this;
    }
}
