<?php

namespace TestApp;


/**
 * Represents router for HTTP requests.
 */
class Router
{

    /** Indicated that auth not required. */
    const AUTH_NONE = 0;
    /** Indicated that auth required. */
    const AUTH_REQUIRED = 1;

    /**
     * Represents list of routes with handler.
     *
     * @var array
     */
    protected $routes = [
        'login' => ['handler' => 'Pages\\Auth'],
        'logout' => ['handler' => 'Pages\\Auth'],

        'index' => ['handler' => 'Pages\\Task'],
        'tasks' => ['handler' => 'Pages\\Task'],
        'tasks/add' => [
            'handler' => 'Pages\\Task',
        ],
        'tasks/change' => [
            'handler' => 'Pages\\Task',
            'auth' => self::AUTH_REQUIRED,
        ],
    ];


    /**
     * Handle given HTTP request depend on defined routes.
     *
     * @param Request $request    HTTP request of application.
     *
     * @throws \Exception    If request wrong and can`t be handled.
     *
     * @return mixed    Returns page controller for given HTTP request.
     */
    public function handle($request)
    {
        if (empty($this->routes[$request->page])) {
            throw new \Exception(sprintf('Page %s not found', $request->page));
        }
        $options = $this->routes[$request->page];
        $className = '\\TestApp\\'.$options['handler'];

        if (isset($options['auth'])
            && $options['auth'] === self::AUTH_REQUIRED
            && !Authorization::$isAuthorized
        ) {
            header('Location: /login');
            exit;
        }

        if (!class_exists($className)) {
            throw new \Exception(sprintf('Class %s not found', $className));
        }
        return new $className($request);
    }
}
