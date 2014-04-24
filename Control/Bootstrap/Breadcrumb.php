<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

namespace phpManufaktur\TemplateTools\Control\Bootstrap;

use Silex\Application;

class Breadcrumb
{
    protected $app = null;
    protected static $options = array(
        'link_home' => true,
        'template_directory' => '@pattern/bootstrap/function/breadcrumb/'
    );

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
     * Check the $options and set self::$options
     *
     * @param array $options
     */
    protected function checkOptions($options)
    {
        if (isset($options['link_home']) && is_bool($options['link_home'])) {
            self::$options['link_home'] = $options['link_home'];
        }
        if (isset($options['template_directory']) && !empty($options['template_directory'])) {
            self::$options['template_directory'] = rtrim($options['template_directory'], '/').'/';
        }
    }

    /**
     * Get information about the given PAGE_ID
     *
     * @param integer $page_id
     */
    protected function getPageInformation($page_id)
    {
        $SQL = "SELECT `page_id`, `link`, `menu_title`, `page_title`, `description` FROM `".
            CMS_TABLE_PREFIX."pages` WHERE `page_id`=".$page_id;
        return $this->app['db']->fetchAssoc($SQL);
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
        if (PAGE_ID < 1) {
            // don't show the breadcrump i.e. at search result pages ...
            return '';
        }

        // first check the $options
        $this->checkOptions($options);

        $SQL = "SELECT `page_trail` FROM `".CMS_TABLE_PREFIX."pages` WHERE `page_id`=".PAGE_ID;
        $page_trails = $this->app['db']->fetchColumn($SQL);

        $breadcrumbs = '';
        $result = '';

        if (!is_null($page_trails) && (strlen($page_trails) > 0)) {
            // create the breadcrumb navigation
            if (strpos($page_trails, ',')) {
                $trails = explode(',', $page_trails);
            }
            else {
                $trails = array(intval($page_trails));
            }
            foreach ($trails as $trail) {
                // walk through the trails of the current page
                $page = $this->getPageInformation($trail);
                $breadcrumbs .= $this->app['twig']->render(
                    self::$options['template_directory'].'li.twig',
                    array(
                        'active' => ($trail == PAGE_ID),
                        'menu_title' => $page['menu_title'],
                        'page_url' => $this->app['cms']->page_url($trail, null, false),
                        'page_title' => $page['page_title'],
                        'page_description' => $page['description']
                    )
                );
            }
            $result = $this->app['twig']->render(
                self::$options['template_directory'].'ol.twig',
                array(
                    'link_home' => self::$options['link_home'],
                    'breadcrumbs' => $breadcrumbs,
                    'active' => false
                )
            );
        }
        elseif (self::$options['link_home']) {
            $result = $this->app['twig']->render(
                self::$options['template_directory'].'ol.twig',
                array(
                    'link_home' => self::$options['link_home'],
                    'breadcrumbs' => $breadcrumbs,
                    'active' => true
                )
            );
        }

        if ($prompt) {
            echo $result;
        }
        else {
            return $result;
        }
    }
}
