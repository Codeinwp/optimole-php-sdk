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

namespace Optimole\Sdk\Exception;

use Optimole\Sdk\ValueObject\OffloadUsage;

class UploadLimitException extends UploadApiException
{
    /**
     * The offload service usage.
     */
    private OffloadUsage $usage;

    /**
     * Constructor.
     */
    public function __construct(OffloadUsage $usage, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->usage = $usage;
    }

    /**
     * Get the offload service usage.
     */
    public function getUsage(): OffloadUsage
    {
        return $this->usage;
    }
}
