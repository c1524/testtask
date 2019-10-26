<?php

namespace TestApp;


/**
 * Represents authorization of application.
 */
class Authorization
{
    /** Contains static admin login. */
    const ADMIN_LOGIN = 'admin';

    /** Contains static admin password. */
    const ADMIN_PASSWORD = '123';


    /**
     * Indicated is client authorized.
     *
     * @var bool
     */
    public static $isAuthorized = false;


    /**
     * Load authorized params from session and check authorized status.
     */
    public function initialize()
    {
        session_start();
        if (($_SESSION['login'] === self::ADMIN_LOGIN)
            && ($_SESSION['password'] === self::ADMIN_PASSWORD)
        ) {
            self::$isAuthorized = true;
        }
    }

    /**
     * Reset client authorization.
     */
    public function reset()
    {
        $_SESSION['login'] = null;
        $_SESSION['password'] = null;
    }

    /**
     * Perform client authorization.
     */
    public function perform($login, $password)
    {
        self::$isAuthorized = false;
        if (($login === self::ADMIN_LOGIN)
            && ($password === self::ADMIN_PASSWORD)
        ) {
            self::$isAuthorized = true;
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
        }
        return self::$isAuthorized;
    }
}
