<?php

use App\Container\Container;
use App\Report\Report;

try {
    $config = require __DIR__ . '/config/container.php';
    if (!\is_array($config)) {
        throw new \Exception('config/container.php must return an array');
    }

    require __DIR__ . '/vendor/autoload.php';

    $container = new Container($config);
    if (!$container->has(Report::class)) {
        throw new \Exception("Can't create " . Report::class);
    }

    $report = $container->get(Report::class);

    echo "\n\e[97;42m OK \e[0m Object \e[32m" . Report::class . "\e[0m was successfully created\n";
} catch (\Exception $e) {
    echo "\e[97;41m ERROR \e[31;49m {$e->getMessage()}\e[0m\n\n";
    echo "\e[36mStack trace\e[0m\n";
    echo $e->getTraceAsString() . "\n";
    die(1);
}
