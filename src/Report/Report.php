<?php

namespace App\Report;

use App\Report\Config\ReportConfigInterface;
use App\Report\Payload\ReportPayloadInterface;

/**
 * Just a fake dependency :)
 */
class Report
{
    public function __construct(
        ReportPayloadInterface $payload,
        ReportConfigInterface $config
    ) {
        echo "\e[97;46m Report \e[0m Got \e[33m" . \get_class($payload) . "\e[0m\n";
        echo "\e[97;46m Report \e[0m Got \e[33m" . \get_class($config) . "\e[0m\n";
    }
}
