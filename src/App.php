<?php

namespace TestApp;


/**
 * Represents TestApp application.
 */
class App
{
    /**
     * Run application and output result.
     *
     * @throws \Exception    If errors during application caused and debug mode
     *                       is on.
     */
    public function run()
    {
        $this->initialize();

        $request = new Request();
        $request->loadFromGlobals();
        (new Authorization())->initialize();
        $router = new Router();
        try {
            $page = $router->handle($request);
            $result = $page->process($request);
            echo $result;
        } catch (\Exception $e) {
            //TODO: Add logs.
            //TODO: If debug mode then rethrow exception.
            throw $e;
        }
    }

    /**
     * Initialize application.
     */
    protected function initialize()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors', 1);
        define('APP_ROOT', dirname(__DIR__));
        Conf::loadFromFile(APP_ROOT.'/conf/app.php');
    }
}
