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

class Nav
{
    protected $app = null;
    protected static $options = array(
        'menu_id' => 0,
        'menu_level' => 0,
        'menu_level_max' => -1,
        'dropdown_link' => array(
            'add' => true,
            'divider' => true
        ),
        'icons' => array(
            'page_id' => array(),
            'height' => 15
        ),
        'visibility' => array(
            'public'
        ),
        'template_directory' => '@pattern/bootstrap/nav/'
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
        if (isset($options['menu_id'])) self::$options['menu_id'] = $options['menu_id'];
        if (isset($options['menu_level'])) self::$options['menu_level'] = $options['menu_level'];
        if (isset($options['dropdown_link']['add'])) self::$options['dropdown_link']['add'] = $options['dropdown_link']['add'];
        if (isset($options['dropdown_link']['divider'])) self::$options['dropdown_link']['divider'] = $options['dropdown_link']['divider'];
        if (isset($options['icons']['page_id']) && is_array($options['icons']['page_id'])) {
            self::$options['icons']['page_id'] = $options['icons']['page_id'];
        }
        if (isset($options['icons']['height']) && is_numeric($options['icons']['height'])) {
            self::$options['icons']['height'] = intval($options['icons']['height']);
        }
        if (isset($options['visibility']) && !empty($options['visibility'])) self::$options['visibility'] = $options['visibility'];
        if (isset($options['template_directory']) && !empty($options['template_directory'])) {
            self::$options['template_directory'] = rtrim($options['template_directory'], '/').'/';
        }
        if (isset($options['menu_level_max']) && is_numeric($options['menu_level_max']) && ($options['menu_level_max'] > -1)) {
            self::$options['menu_level_max'] = intval($options['menu_level_max']);
        }
    }

    /**
     * Get the Page ID's for the given menu, level and visibility
     *
     * @param integer $menu
     * @param integer $level
     * @param string $visibility
     * @throws \Exception
     * @return Ambigous <boolean, array>
     */
    protected function getPageIDsForLevel($menu = 0, $level = 0, $page_id=null)
    {
        $menu_sql = ($menu > 0) ? "AND `menu` = $menu " : '';

        $visibility = '';
        foreach (self::$options['visibility'] as $visi) {
            if (!empty($visibility)) {
                $visibility .= ' OR ';
            }
            $visibility .= "`visibility`='$visi'";
        }

        $SQL = "SELECT `page_id` FROM `".CMS_TABLE_PREFIX."pages` WHERE `level`= $level $menu_sql" . "AND ($visibility)";
        if (!is_null($page_id)) {
            $SQL .= " AND `parent`='$page_id'";
        }
        $SQL .= " ORDER BY `position` ASC";

        $page_ids = array();

        $results = $this->app['db']->fetchAll($SQL);

        if (is_array($results)) {
            foreach ($results as $result) {
                $page_ids[] = $result['page_id'];
            }
        }
        return (!empty($page_ids)) ? $page_ids : false;
    }

    /**
     * Get information about the given menu
     *
     * @param integer $page_id
     * @throws Exception
     * @return array|boolean
     */
    protected function getMenuInformation($page_id)
    {
        $SQL = "SELECT `page_id`, `parent`, `root_parent`, `position`, `level`, `menu_title`, ".
            "`page_title`, `link` FROM `".CMS_TABLE_PREFIX."pages` WHERE `page_id`=$page_id";
        $result = $this->app['db']->fetchAssoc($SQL);

        if (isset($result['parent'])) {
            $result['active'] = (PAGE_ID == $page_id);
            $result['url'] = CMS_URL . CMS_PAGES_DIRECTORY . $result['link'] . PAGE_EXTENSION;
            $result['icons'] = self::$options['icons'];
            return $result;
        }
        else {
            return false;
        }
    }

    /**
     * Check if the given PAGE ID has a child
     *
     * @param integer $page_id
     * @throws \Exception
     * @return boolean
     */
    protected function PageHasChild($page_id)
    {
        $SQL = "SELECT `page_id` FROM `".CMS_TABLE_PREFIX."pages` WHERE `parent` = $page_id LIMIT 1";
        $page_id = $this->app['db']->fetchColumn($SQL);
        return ($page_id > 0);
    }

    /**
     * Build the nav items recursivly
     *
     * @param integer $menu
     * @param integer $level
     * @param string $visibility
     * @param string reference $nav
     */
    protected function buildNav($menu, $level, $visibility, $page_id=null, &$nav = '')
    {
        $page_ids = $this->getPageIDsForLevel($menu, $level, $page_id, $visibility);

        if (is_array($page_ids)) {
            foreach ($page_ids as $page_id) {
                if (false !== ($info = $this->getMenuInformation($page_id))) {
                    if ($this->PageHasChild($page_id) && ((self::$options['menu_level_max'] < 0) || (self::$options['menu_level_max'] > $level))) {
                        if ($level == self::$options['menu_level']) {
                            $nav .= $this->app['twig']->render(
                                self::$options['template_directory'].'li.dropdown.twig',
                                array('menu' => $info));
                            $info['hint'] = $this->app['translator']->trans('Click to toggle dropdown menu');
                            $info['direction'] = 'down';
                            $nav .= $this->app['twig']->render(
                                self::$options['template_directory'].'a.dropdown-toggle.twig',
                                array('menu' => $info));
                        }
                        else {
                            $nav .= $this->app['twig']->render(
                                self::$options['template_directory'].'li.dropdown-submenu.twig',
                                array('menu' => $info));
                            $info['hint'] = $this->app['translator']->trans('Click to toggle dropdown menu');
                            $info['direction'] = 'right';
                            $nav .= $this->app['twig']->render(
                                self::$options['template_directory'].'a.dropdown-toggle.twig',
                                array('menu' => $info));
                        }

                        $nav .= $this->app['twig']->render(
                            self::$options['template_directory'].'ul.dropdown-menu.twig');
                        if (self::$options['dropdown_link']['add']) {
                            // add an additional dropdown link to enable selection
                            $nav .= $this->app['twig']->render(
                                self::$options['template_directory'].'li.menu-item.twig',
                                array('menu' => $info));
                            if (self::$options['dropdown_link']['divider']) {
                                // add a divider to separate the link from the others
                                $nav .= $this->app['twig']->render(
                                    self::$options['template_directory'].'li.divider.twig');
                            }
                        }
                        $this->buildNav($menu, $level+1, $visibility, $page_id, $nav);
                        $nav .= $this->app['twig']->render(
                            self::$options['template_directory'].'ul.li.close.twig');
                    }
                    else {
                        $nav .= $this->app['twig']->render(
                            self::$options['template_directory'].'li.menu-item.twig',
                            array('menu' => $info));
                    }
                }
            }
        }
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
        $this->checkOptions($options);

        $nav = '';
        $this->buildNav(self::$options['menu_id'], self::$options['menu_level'], self::$options['visibility'], null, $nav);

        if (!empty($nav)) {
            $nav = $this->app['twig']->render(
                self::$options['template_directory'].'ul.twig',
                array('nav' => $nav, 'class' => $class));
        }

        if ($prompt) {
            echo $nav;
        }
        else {
            return $nav;
        }
    }
}
