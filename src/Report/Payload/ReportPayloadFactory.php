<?php

namespace App\Report\Payload;

use Psr\Container\ContainerInterface;

/**
 * Invokable factory example
 */
class ReportPayloadFactory
{
    public function __invoke(ContainerInterface $container): ReportPayloadInterface
    {
        return new ReportPayload();
    }
}
