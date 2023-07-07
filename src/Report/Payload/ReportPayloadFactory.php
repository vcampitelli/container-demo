<?php

namespace App\Report\Payload;

/**
 * Invokable factory example
 */
class ReportPayloadFactory
{
    public function __invoke(): ReportPayloadInterface
    {
        return new ReportPayload();
    }
}
