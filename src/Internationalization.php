<?php

namespace App;

/**
 * Just a fake dependency :)
 */
class Internationalization
{
    public function __construct(string $language)
    {
        echo "\e[97;46m Internationalization \e[0m Got \e[33m" . $language . "\e[0m\n";
    }
}
