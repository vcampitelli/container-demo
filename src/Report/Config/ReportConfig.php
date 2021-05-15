<?php

namespace App\Report\Config;

use App\Internationalization;

/**
 * Just a fake dependency :)
 */
class ReportConfig implements ReportConfigInterface
{
    public function __construct(Internationalization $i18n)
    {
        echo "\e[97;46m ReportConfig \e[0m Got \e[33m" . \get_class($i18n) . "\e[0m\n";
    }
}
