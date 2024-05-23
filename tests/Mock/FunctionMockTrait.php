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

namespace Optimole\Sdk\Tests\Mock;

use phpmock\phpunit\PHPMock;

/**
 * Adds mocking methods for mocking PHP functions.
 */
trait FunctionMockTrait
{
    use PHPMock;

    /**
     * Get the namespace of the given class.
     */
    private function getNamespace(string $className): string
    {
        return (new \ReflectionClass($className))->getNamespaceName();
    }
}
