<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

namespace phpManufaktur\TemplateTools\Control;

use Silex\Application;
use phpManufaktur\TemplateTools\Control\Bootstrap\Nav;
use phpManufaktur\TemplateTools\Control\Bootstrap\Breadcrumb;

class Bootstrap
{
    protected $app = null;
    protected $Nav = null;
    protected $Breadcrumb = null;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->Nav = new Nav($app);
        $this->Breadcrumb = new Breadcrumb($app);
    }

    /**
     * Create a unsorted list for the Bootstrap nav components
     *
     * @param string $class
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function nav($class, $options=array(), $prompt=true)
    {
        return $this->Nav->nav($class, $options, $prompt);
    }

    /**
     * Create a breadcrumb navigation
     *
     * @param array $options
     * @param boolean $prompt
     * @return string breadcrumb
     */
    public function breadcrumb($options=array(), $prompt=true)
    {
        return $this->Breadcrumb->breadcrumb($options, $prompt);
    }

}
