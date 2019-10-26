<?php

namespace TestApp\Traits;

use \TestApp\Conf;


/**
 * Provide class methods to database connection.
 */
trait MySQL
{
    /**
     * MySQL connection instance.
     *
     * @var null|\PDO
     */
    protected static $Connection = null;

    /**
     * Initialize MySQL connection if yet not initialized.
     *
     * @return null|\PDO    Returns initialized twig instance.
     */
    protected function initMySQL()
    {
        if (!is_null(self::$Connection)) {
            return self::$Connection;
        }
        self::$Connection = new \PDO(
            'mysql:host='.Conf::$MySQL['host'].';dbname='.
                Conf::$MySQL['database'],
            Conf::$MySQL['username'],
            Conf::$MySQL['password'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]
        );
        return self::$Connection;
    }
}
