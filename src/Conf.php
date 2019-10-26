<?php

namespace TestApp;


/**
 * Represents configuration for application.
 */
class Conf
{
    /**
     * Is debug mode is on.
     *
     * @var bool
     */
    public static $isDebugMode = false;

    /**
     * Contains setting for MySQL connection.
     *
     * @var array
     */
    public static $MySQL = [];

    /**
     * Read given configuration file and load it settings.
     *
     * @param string $file    Specified configuration file.
     */
    public static function loadFromFile($file)
    {
        require $file;
    }
}
