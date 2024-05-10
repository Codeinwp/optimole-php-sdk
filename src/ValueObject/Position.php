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

namespace Optimole\Sdk\ValueObject;

use Optimole\Sdk\Exception\InvalidArgumentException;

final class Position
{
    /**
     * Center.
     */
    public const CENTER = 'ce';

    /**
     * Right Edge.
     */
    public const EAST = 'ea';

    /**
     * Top edge.
     */
    public const NORTH = 'no';

    /**
     * Top left corner.
     */
    public const NORTH_EAST = 'nowe';

    /**
     * Top right corner.
     */
    public const NORTH_WEST = 'noea';

    /**
     * Bottom Edge.
     */
    public const SOUTH = 'so';

    /**
     * Bottom right corner.
     */
    public const SOUTH_EAST = 'soea';

    /**
     * Bottom left corner.
     */
    public const SOUTH_WEST = 'sowe';

    /**
     * Left edge.
     */
    public const WEST = 'we';

    /**
     * The position.
     */
    private string $position;

    /**
     * Constructor.
     */
    public function __construct(string $position)
    {
        if (!self::isValid($position)) {
            throw new InvalidArgumentException(sprintf('Position "%s" is invalid.', $position));
        }

        $this->position = $position;
    }

    /**
     * Convert the position to its string representation.
     */
    public function __toString(): string
    {
        return $this->position;
    }

    /**
     * Validates if the given value is one of the defined position constants.
     */
    public static function isValid($value): bool
    {
        return in_array($value, (new \ReflectionClass(__CLASS__))->getConstants());
    }
}
