<?php

use App\Report\Config\ReportConfig;
use App\Report\Config\ReportConfigInterface;
use App\Report\Payload\ReportPayload;
use App\Report\Payload\ReportPayloadFactory;
use App\Report\Payload\ReportPayloadInterface;
use App\Report\Report;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        // Example 1: via function
        Report::class => function (ContainerInterface $container) {
            return new Report(
                $container->get(ReportPayloadInterface::class),
                $container->get(ReportConfigInterface::class)
            );
        },

        // Example 2: via invokable factory class
        ReportPayload::class => ReportPayloadFactory::class,
    ],

    'aliases' => [
        // Example 3: providing an alias (useful for determining implementations for interfaces)
        ReportPayloadInterface::class => ReportPayload::class,

        // Example 4: providing an alias but with automagic injections via ReflectionClass
        ReportConfigInterface::class => ReportConfig::class,
    ],

    'settings' => [
        // Example 5: custom scalar values (the constructor argument should be named exactly like this)
        'language' => 'pt-br',
    ],
];
