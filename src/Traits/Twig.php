<?php

namespace TestApp\Traits;


/**
 * Provide class method to render page.
 */
trait Twig
{
    /**
     * Twig environment instance.
     *
     * @var null|\Twig\Environment
     */
    protected static $Twig = null;

    /**
     * Initialize twig environment if yet not initialized.
     *
     * @return null|\Twig\Environment    Returns initialized twig instance.
     */
    protected function initTwig()
    {
        if (!is_null(self::$Twig)) {
            return self::$Twig;
        }
        $loader = new \Twig\Loader\FilesystemLoader(APP_ROOT.'/templates');
        return (self::$Twig = new \Twig\Environment($loader));
    }

    /**
     * Render given page with given params.
     *
     * @param string $alias    Specified alias of page template.
     * @param array $params    Parameters to render page with it.
     *
     * @return string    Return rendered page code.
     */
    protected function renderPage($alias, $params)
    {
        return $this->initTwig()->render($alias.'.twig', $params);
    }
}
