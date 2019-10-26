<?php

namespace TestApp;


/**
 * Represents HTTP request to application.
 */
class Request
{
    /**
     * Contains GET values of HTTP request.
     *
     * @var array
     */
    public $get = [];

    /**
     * Contains POST values of HTTP request.
     *
     * @var array
     */
    public $post = [];

    /**
     * Contains requested page.
     *
     * @var null|string
     */
    public $page = null;


    /**
     * Load request details from global vars.
     */
    public function loadFromGlobals()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->page = preg_replace('/^\//', '', $path);
    }
}
