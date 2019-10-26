<?php

namespace TestApp\Pages;

use \TestApp\Authorization;
use \TestApp\Request;
use \TestApp\Traits;


/**
 * Represents auth controller.
 */
class Auth
{
    use Traits\Twig;


    /**
     * Process given request and return its result
     *
     * @param Request $request    HTTP request of application.
     *
     * @throws \Exception    If request wrong and can`t be handled.
     *
     * @return string    Returns HTML output of handled request.
     */
    public function process($request)
    {
        switch ($request->page) {
        case 'login':
            return $this->runLoginAction($request);
        case 'logout':
            return $this->runLogoutAction();
        default:
            throw new \Exception('Unhandled request: '.$request->page);
        }
    }

    /**
     * Run login action and return login page with errors if any exists.
     *
     * @param Request $request    HTTP request of application.
     *
     * @return string    Returns HTML output of login page.
     */
    protected function runLoginAction($request)
    {
        $errors = [];

        if (isset($request->post['doAuth'])) {
            if (empty($login = $request->post['login'])) {
                $errors[] = 'login_unknown';
            }
            if (empty($password = $request->post['password'])) {
                $errors[] = 'password_unknown';
            }
            if (empty($errors)) {
                if ((new Authorization())->perform($login, $password)) {
                    header('Location: /index');
                    exit;
                } else {
                    $errors[] = 'auth_fail';
                }
            }
        }

        return $this->renderPage('auth_login', [
            'errors' => $errors,
            'login' => $request->post['login'],
        ]);
    }

    /**
     * Run logout action and make redirect to index page.
     */
    protected function runLogoutAction()
    {
        (new Authorization())->reset();
        header('Location: /index');
        exit;
    }
}
