<?php

namespace App\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * NotFoundException constructor.
     *
     * @param string $message
     * @param string $serviceId
     * @param Throwable|null $previous
     */
    public function __construct(string $message, string $serviceId, Throwable $previous = null)
    {
        $message = "Service {$serviceId} not found: {$message}";
        parent::__construct($message, 0, $previous);
    }
}
