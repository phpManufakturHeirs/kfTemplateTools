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
use phpManufaktur\TemplateTools\Control\Bootstrap\Breadcrumb;
use phpManufaktur\TemplateTools\Control\Bootstrap\Pager;
use phpManufaktur\TemplateTools\Control\Classic\SocialSharingButtons;

class Classic
{
    protected $app = null;
    protected $BootstrapBreadcrumb = null;
    protected $BootstrapPager = null;
    protected $SocialSharingButtons = null;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->BootstrapBreadcrumb = new Breadcrumb($app);
        $this->BootstrapPager = new Pager($app);
        $this->SocialSharingButtons = new SocialSharingButtons($app);
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
        // we are using the Bootstrap Breadcrumb function!
        if (!isset($options['template_directory'])) {
            // the only difference are the used templates ...
            $options['template_directory'] = '@pattern/classic/function/breadcrumb/';
        }
        return $this->BootstrapBreadcrumb->breadcrumb($options, $prompt);
    }

    /**
     * Create a Pager to step through the site
     *
     * @param array $options
     * @param boolean $prompt
     * @return string
     */
    public function pager($options=array(), $prompt=true)
    {
        // we are using the Bootstrap Breadcrumb function!
        if (!isset($options['template_directory'])) {
            // the only difference are the used templates ...
            $options['template_directory'] = '@pattern/classic/function/pager/';
        }
        return $this->BootstrapPager->pager($options, $prompt);
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
