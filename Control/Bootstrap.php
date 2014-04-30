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
    protected $Nav = null;
    protected $Breadcrumb = null;
    protected $Pager = null;
    protected $Alert = null;
    protected $SocialSharingButtons = null;

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
        $this->Pager = new Pager($app);
        $this->Alert = new Alert($app);
        $this->SocialSharingButtons = new SocialSharingButtons($app);
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

    /**
     * Create a Bootstrap Pager to step through the site
     *
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function pager($options=array(), $prompt=true)
    {
        return $this->Pager->pager($options, $prompt);
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
        return $this->Alert->alert($message, $options, $prompt);
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
        return $this->SocialSharingButtons->social_sharing_buttons($buttons, $options, $prompt);
    }
}
