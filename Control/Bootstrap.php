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
use phpManufaktur\TemplateTools\Control\Bootstrap\Pager;
use phpManufaktur\TemplateTools\Control\Bootstrap\Alert;
use phpManufaktur\TemplateTools\Control\Classic\SocialSharingButtons;

class Bootstrap
{
    protected $app = null;
    
    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Create a unsorted list for the Bootstrap nav components
     *
     * @param string $class
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function nav($class='nav nav-tabs', $options=array(), $prompt=true)
    {
        $Nav = new Nav($this->app);
        return $Nav->nav($class, $options, $prompt);
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
        $Breadcrumb = new Breadcrumb($app);
        return $Breadcrumb->breadcrumb($options, $prompt);
    }

    /**
     * Create a Bootstrap Pager to step through the site
     *
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function pager($options=array(), $prompt=true)
    {
        $Pager = new Pager($app);
        return $Pager->pager($options, $prompt);
    }
    
    /**
     * Use the Bootstrap Alert Component to alert a message
     * 
     * @param string $message
     * @param array $options
     * @param boolean $prompt
     * @return string rendered alert
     */
    public function alert($message='', $options=array(), $prompt=true)
    {
        $Alert = new Alert($app);
        return $Alert->alert($message, $options, $prompt);
    }
    
    /**
     * Create responsive social sharing buttons
     *
     * @param array $buttons
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function social_sharing_buttons($buttons=array(), $options=array(), $prompt=true)
    {
        $SocialSharingButtons = new SocialSharingButtons($app);
        return $SocialSharingButtons->social_sharing_buttons($buttons, $options, $prompt);
    }
}
