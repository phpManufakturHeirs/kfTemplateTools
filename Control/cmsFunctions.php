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
use phpManufaktur\Basic\Data\CMS\Page;
use phpManufaktur\TemplateTools\Control\CMS\PageImage;


class cmsFunctions
{
    protected $app = null;
    protected $PageData = null;
    private static $page_sequence = null;
    private static $page_block = null;
    private static $page_menu = null;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->PageData = new Page($app);

        // use the origin constants, our own maybe not defined yet ...
        $info_path = WB_PATH.substr(TEMPLATE_DIR, strlen(WB_URL)).'/info.php';
        if ($app['filesystem']->exists($info_path)) {
            global $block;
            global $menu;
            require_once $info_path;
            if (is_array($block)) {
                self::$page_block = $block;
            }
            if (is_array($menu)) {
                self::$page_menu = $menu;
            }
        }
    }

    /**
     * Return the page description for the actual PAGE_ID.
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the description for TOPICS oder NEWS
     *
     * @param array $arguments
     * @param boolean $prompt
     * @return string
     */
    public function page_description($arguments=null, $prompt=true)
    {
        if (isset($arguments['topic_id']) && (is_int($arguments['topic_id'])) && ($arguments['topic_id'] > 0)) {
            if (!file_exists(CMS_ADDONS_PATH . '/topics/module_settings.php')) {
                throw new \Exception('A TOPIC_ID was submitted, but TOPICS is not installed!');
            }
            // get the title
            $SQL = "SELECT `description` FROM `".CMS_TABLE_PREFIX."mod_topics` WHERE `topic_id`=".$arguments['topic_id'];
            $description = $this->app['db']->fetchColumn($SQL);
            if ($prompt) {
                echo $description;
            }
            else {
                return $description;
            }
        }
        elseif (isset($arguments['post_id']) && is_int($arguments['post_id']) && ($arguments['post_id'] > 0)) {
            // indicate a NEWS page
            if (!file_exists(CMS_PATH. '/modules/news/info.php')) {
                throw new \Exception('A POST_ID was submitted, but the NEWS addon is not installed at the parent CMS!');
            }
            // there is no description available, so we return the CMS_DESCRIPTION
            if ($prompt) {
                echo CMS_DESCRIPTION;
            }
            else {
                return CMS_DESCRIPTION;
            }
        }
        elseif (function_exists('page_description')) {
            if ($prompt) {
                \page_description();
            }
            else {
                ob_start();
                \page_description();
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return the page title for the actual PAGE_ID
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the title for TOPICS oder NEWS
     *
     * @param array $arguments
     * @param boolean $prompt
     * @param string $spacer
     * @param string $template
     * @return string
     */
    public function page_title($arguments=null, $prompt=true, $spacer= ' - ', $template='[PAGE_TITLE]')
    {
        if (isset($arguments['topic_id']) && (is_int($arguments['topic_id'])) && ($arguments['topic_id'] > 0)) {
            if (!file_exists(CMS_ADDONS_PATH . '/topics/module_settings.php')) {
                throw new \Exception('A TOPIC_ID was submitted, but TOPICS is not installed!');
            }
            // get the title
            $SQL = "SELECT `title` FROM `".CMS_TABLE_PREFIX."mod_topics` WHERE `topic_id`=".$arguments['topic_id'];
            $title = $this->app['db']->fetchColumn($SQL);

            $placeholders = array('[WEBSITE_TITLE]', '[PAGE_TITLE]', '[MENU_TITLE]', '[SPACER]');
            $values = array(WEBSITE_TITLE, $title, MENU_TITLE, $spacer);

            if ($prompt) {
                echo str_replace($placeholders, $values, $template);
            }
            else {
                return str_replace($placeholders, $values, $template);
            }
        }
        elseif (isset($arguments['post_id']) && is_int($arguments['post_id']) && ($arguments['post_id'] > 0)) {
            // indicate a NEWS page
            if (!file_exists(CMS_PATH. '/modules/news/info.php')) {
                throw new \Exception('A POST_ID was submitted, but the NEWS addon is not installed at the parent CMS!');
            }
            $SQL = "SELECT `title` FROM `".CMS_TABLE_PREFIX."mod_news_posts` WHERE `post_id`='".$arguments['post_id']."'";
            $title = $this->app['db']->fetchColumn($SQL);

            $placeholders = array('[WEBSITE_TITLE]', '[PAGE_TITLE]', '[MENU_TITLE]', '[SPACER]');
            $values = array(WEBSITE_TITLE, $title, MENU_TITLE, $spacer);

            if ($prompt) {
                echo str_replace($placeholders, $values, $template);
            }
            else {
                return str_replace($placeholders, $values, $template);
            }
        }
        elseif (function_exists('page_title')) {
            if ($prompt) {
                \page_title($spacer, $template);
            }
            else {
                ob_start();
                \page_title($spacer, $template);
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return the page keywords for the actual PAGE_ID
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the keywords for TOPICS oder NEWS
     *
     * @param boolean $prompt
     * @return string
     */
    public function page_keywords($arguments=null, $prompt=true)
    {
        if (isset($arguments['topic_id']) && (is_int($arguments['topic_id'])) && ($arguments['topic_id'] > 0)) {
            if (!file_exists(CMS_ADDONS_PATH . '/topics/module_settings.php')) {
                throw new \Exception('A TOPIC_ID was submitted, but TOPICS is not installed!');
            }
            // get the title
            $SQL = "SELECT `keywords` FROM `".CMS_TABLE_PREFIX."mod_topics` WHERE `topic_id`=".$arguments['topic_id'];
            $keywords = $this->app['db']->fetchColumn($SQL);
            if ($prompt) {
                echo keywords;
            }
            else {
                return $keywords;
            }
        }
        elseif (isset($arguments['post_id']) && is_int($arguments['post_id']) && ($arguments['post_id'] > 0)) {
            // indicate a NEWS page
            if (!file_exists(CMS_PATH. '/modules/news/info.php')) {
                throw new \Exception('A POST_ID was submitted, but the NEWS addon is not installed at the parent CMS!');
            }
            // there are no keywords available, so we return the CMS_KEYWORDS
            if ($prompt) {
                echo CMS_KEYWORDS;
            }
            else {
                return CMS_KEYWORDS;
            }
        }
        elseif (function_exists('page_keywords')) {
            if ($prompt) {
                \page_keywords();
            }
            else {
                ob_start();
                \page_keywords();
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return the page content by the given block for the actual PAGE_ID
     *
     * @param number|string $block
     * @param boolean $prompt
     * @return string
     */
    public function page_content($block=1, $prompt=true)
    {
        if (!is_numeric($block) && is_string($block) && is_array(self::$page_block)) {
            // try to get the Block ID by the Block Name
            $search = $block;
            $block = null;
            foreach (self::$page_block as $id => $name) {
                if (strtolower($search) == strtolower($name)) {
                    $block = $id;
                    break;
                }
            }
        }

        if (function_exists('page_content')) {
            if ($prompt) {
                \page_content($block);
            }
            else {
                ob_start();
                \page_content($block);
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Mapping the show_menu2()
     *
     * @param number|string $aMenu
     * @param string $aStart
     * @param unknown $aMaxLevel
     * @param string $aOptions
     * @param string $aItemOpen
     * @param string $aItemClose
     * @param string $aMenuOpen
     * @param string $aMenuClose
     * @param string $aTopItemOpen
     * @param string $aTopMenuOpen
     * @param boolean $prompt
     * @return Ambigous <boolean, string, unknown>
     */
    public function show_menu2(
        $aMenu          = 0,
        $aStart         = SM2_ROOT,
        $aMaxLevel      = -1999, // SM2_CURR+1
        $aOptions       = SM2_TRIM,
        $aItemOpen      = false,
        $aItemClose     = false,
        $aMenuOpen      = false,
        $aMenuClose     = false,
        $aTopItemOpen   = false,
        $aTopMenuOpen   = false,
        $prompt         = true
        )
    {
        if (!is_numeric($aMenu) && is_string($aMenu) && !empty($aMenu) && is_array(self::$page_menu)) {
            // try to get the Menu ID by the Menu Name
            $search = $aMenu;
            $aMenu = 999; // set Menu ID to a non existing one
            foreach (self::$page_menu as $id => $name) {
                if (strtolower($search) == strtolower($name)) {
                    $aMenu = $id;
                    break;
                }
            }
        }

        if (function_exists('show_menu2')) {
            if ($prompt) {
                \show_menu2($aMenu,$aStart,$aMaxLevel,$aOptions,$aItemOpen,
                    $aItemClose,$aMenuOpen,$aMenuClose,$aTopItemOpen,$aTopMenuOpen);
            }
            else {
                ob_start();
                \show_menu2($aMenu,$aStart,$aMaxLevel,$aOptions,$aItemOpen,
                    $aItemClose,$aMenuOpen,$aMenuClose,$aTopItemOpen,$aTopMenuOpen);
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Get the URL of the given page ID. If arguments 'topic_id' or 'post_id'
     * the function will return the URL for the given TOPICS or NEWS article
     *
     * @param null|array $arguments
     * @param boolean $prompt
     * @throws \Exception
     * @return string URL of the page
     */
    public function page_url($page_id=PAGE_ID, $arguments=null, $prompt=true)
    {
        if ($prompt) {
            echo $this->PageData->getURL($page_id, $arguments);
        }
        else {
            return $this->PageData->getURL($page_id, $arguments);
        }
    }

    /**
     * Return the page footer from the CMS options.
     * You can use the placeholders [YEAR] and [PROCESS_TIME]
     *
     * @param string $date_format for the [YEAR] placeholder
     * @param boolean $prompt
     * @return string formatted
     */
    public function page_footer($date_format='Y', $prompt=true)
    {
        if (function_exists('page_footer')) {
            if ($prompt) {
                \page_footer($date_format);
            }
            else {
                ob_start();
                \page_footer($date_format);
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return the page header from the CMS options.
     *
     * @param boolean $prompt
     */
    public function page_header($prompt=true)
    {
        if (function_exists('page_header')) {
            if ($prompt) {
                \page_header();
            }
            else {
                ob_start();
                \page_header();
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Function to add optional module Javascript or CSS stylesheets into the
     * <head> section of the frontend
     *
     * @param string $file_type
     * @param boolean $prompt
     * @return string
     */
    public function register_frontend_modfiles($file_type='css', $prompt=true)
    {
        if (function_exists('register_frontend_modfiles')) {
            if ($prompt) {
                \register_frontend_modfiles($file_type);
            }
            else {
                ob_start();
                \register_frontend_modfiles($file_type);
                return ob_get_clean();
            }
        }
        else {
            return null;
        }
    }

    /**
     * Function to add optional frontend_body.js files before the </body> tag.
     * This function override the original and does NOT include jQuery files
     * into the <head> section.
     *
     * @param boolean $prompt
     * @return string
     */
    public function register_frontend_modfiles_body($prompt=true)
    {
        global $include_body_links;

        if (!defined('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED')) {
            define('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true);
        }

        $body_links = '';
        $base_link = '<script src="'.CMS_URL.'/modules/{MODULE_DIRECTORY}/frontend_body.js" type="text/javascript"></script>';
        $base_file = "frontend_body.js";

        // ensure that frontend_body.js is only added once per module type
        if (!empty($include_body_links)) {
            if (strpos($body_links, $include_body_links) === false) {
                $body_links .= $include_body_links;
            }
            $include_body_links = '';
        }

        // gather information for all models embedded on actual page
        $SQL = "SELECT `module` FROM `".CMS_TABLE_PREFIX."sections` WHERE `page_id`=".PAGE_ID." AND `module`<>'wysiwyg'";
        $modules = $this->app['db']->fetchAll($SQL);
        if (is_array($modules)) {
            foreach ($modules as $module) {
                if ($this->app['filesystem']->exists(CMS_PATH.'/modules/'.$module['module'].'/'.$base_file)) {
                    // create link with frontend_body.js source for the current module
                    $tmp_link = str_replace("{MODULE_DIRECTORY}", $module['module'], $base_link);
                    // define constant indicating that the register_frontent_files_body was invoked
                    if (!defined('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED')) {
                        define('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true);
                    }
                    // ensure that frontend_body.js is only added once per module type
                    if (strpos($body_links, $tmp_link) === false) {
                        $body_links .= $tmp_link;
                    }
                }
            }
        }

        if ($prompt) {
            echo $body_links;
        }
        else {
            return $body_links;
        }
    }

    /**
     * Get the previous page ID for the given page ID
     *
     * @param integer $page_id
     * @param array $page_visibility
     * @param boolean $prompt
     * @return integer
     */
    public function page_previous_id($page_id=PAGE_ID, $page_visibility=array('public'), $prompt=true)
    {
        if (!is_array($page_visibility) || empty($page_visibility)) {
            $page_visibility = array('public');
        }

        $SQL = "SELECT `menu` FROM `".CMS_TABLE_PREFIX."pages` WHERE `page_id`=$page_id";
        if (null == ($menu = $this->app['db']->fetchColumn($SQL))) {
            // no result - possible at search pages etc.
            return -1;
        }

        // get the page sequence
        $sequence = $this->page_sequence($menu, 0, $page_visibility);

        $result = -1;

        if (false !== ($key = array_search($page_id, $sequence))) {
            if (isset($sequence[$key-1])) {
                $result = $sequence[$key-1];
            }
        }

        if ($prompt) {
            echo $result;
        }
        return $result;
    }


    /**
     * Get the next page ID for the given page ID
     *
     * @param integer $page_id
     * @param array $page_visibility
     * @param boolean $prompt
     * @return integer
     */
    public function page_next_id($page_id=PAGE_ID, $page_visibility=array('public'), $prompt=true)
    {
        if (!is_array($page_visibility) || empty($page_visibility)) {
            $page_visibility = array('public');
        }

        $SQL = "SELECT `menu` FROM `".CMS_TABLE_PREFIX."pages` WHERE `page_id`=$page_id";
        if (null == ($menu = $this->app['db']->fetchColumn($SQL))) {
            // no result - possible at search pages etc.
            return -1;
        }

        // get the page sequence
        $sequence = $this->page_sequence($menu, 0, $page_visibility);

        $result = -1;

        if (false !== ($key = array_search($page_id, $sequence))) {
            if (isset($sequence[$key+1])) {
                $result = $sequence[$key+1];
            }
        }

        if ($prompt) {
            echo $result;
        }
        return $result;
    }

    /**
     * Callback function to add a page ID to the page sequence array
     *
     * @param integer $id
     * @see page_sequence()
     */
    private static function add_page_sequence_id($id)
    {
        self::$page_sequence[] = $id;
    }

    /**
     * Create a array with the complete page sequence for the given menu, level
     * and visibility. Can be used to create a sitemap or step through the site.
     *
     * @param number $menu
     * @param number $start_level
     * @param array $page_visibility
     */
    public function page_sequence($menu=1, $start_level=0, $page_visibility=array('public'))
    {
        if (!is_array($page_visibility) || empty($page_visibility)) {
            $page_visibility = array('public');
        }
        $visibility = '';
        foreach ($page_visibility as $visi) {
            if (!empty($visibility)) {
                $visibility .= ' OR ';
            }
            $visibility .= "`visibility`='$visi'";
        }

        $sequence = array();

        $SQL = "SELECT `page_id` FROM `".CMS_TABLE_PREFIX."pages` WHERE `menu`=$menu AND ".
            "`level`=$start_level AND ($visibility) ORDER BY `position` ASC";
        $pages = $this->app['db']->fetchAll($SQL);

        foreach ($pages as $page) {
            $sequence[$page['page_id']][] = $page['page_id'];
        }

        $SQL = "SELECT MAX(`level`) FROM `".CMS_TABLE_PREFIX."pages` WHERE `menu`=$menu";
        $max_level = $this->app['db']->fetchColumn($SQL);

        for ($level=$start_level+1; $level < $max_level+1; $level++) {
            $SQL = "SELECT `page_id`, `page_trail` FROM `".CMS_TABLE_PREFIX."pages` WHERE ".
                "`menu`=$menu AND `level`=$level AND ($visibility) ORDER BY `position` ASC";
            $pages = $this->app['db']->fetchAll($SQL);
            foreach ($pages as $page) {
                $t = explode(',', $page['page_trail']);
                switch ($level) {
                    case 1:
                        $sequence[$t[0]][$t[1]][] = $page['page_id'];
                        break;
                    case 2:
                        $sequence[$t[0]][$t[1]][$t[2]][] = $page['page_id'];
                        break;
                    case 3:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][] = $page['page_id'];
                        break;
                    case 4:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][] = $page['page_id'];
                        break;
                    case 5:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][$t[5]][] = $page['page_id'];
                        break;
                    case 6:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][$t[5]][$t[6]][] = $page['page_id'];
                        break;
                    case 7:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][$t[5]][$t[6]][$t[7]][] = $page['page_id'];
                        break;
                    case 8:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][$t[5]][$t[6]][$t[7]][$t[8]][] = $page['page_id'];
                        break;
                    case 9:
                        $sequence[$t[0]][$t[1]][$t[2]][$t[3]][$t[4]][$t[5]][$t[6]][$t[7]][$t[8]][$t[9]][] = $page['page_id'];
                        break;
                }
            }
        }

        // reset sequence array
        self::$page_sequence = array();
        // create the page sequence
        array_walk_recursive($sequence, array($this, 'add_page_sequence_id'));

        return self::$page_sequence;
    }

    /**
     * Check if the given PAGE ID has a child
     *
     * @param integer $page_id
     * @throws \Exception
     * @return boolean
     */
    public function page_has_child($page_id=PAGE_ID)
    {
        if ($page_id > 0) {
            $SQL = "SELECT `page_id` FROM `".CMS_TABLE_PREFIX."pages` WHERE `parent` = $page_id LIMIT 1";
            $page_id = $this->app['db']->fetchColumn($SQL);
            return ($page_id > 0);
        }
        else {
            return false;
        }
    }

    /**
     * Return the WYSIWYG content of the given section ID
     *
     * @param integer $section_id
     * @param boolean $prompt
     * @throws \InvalidArgumentException
     * @return string
     */
    public function wysiwyg_content($section_id, $prompt=true)
    {
        if (false === ($section_id = filter_var($section_id, FILTER_VALIDATE_INT))) {
            throw new \InvalidArgumentException('The $section_id must be of type integer!');
        }
        $SQL = "SELECT `content` FROM `".CMS_TABLE_PREFIX."mod_wysiwyg` WHERE `section_id`=$section_id";
        $content = $this->app['db']->fetchColumn($SQL);
        $result = (!empty($content)) ? $this->app['tools']->unsanitizeText($content) : '';
        if ($prompt) {
            echo $result;
        }
        return $result;
    }
    
    /**
     * Get the first content image from any WYSIWYG, NEWS, TOPICS or flexContent article.
     * Try alternate to get a teaser image (TOPICS, flexContent)
     * 
     * @param integer $page_id
     * @param array $options
     * @return string return the URL of the image or an empty string
     */
    public function page_image($page_id=PAGE_ID, $options=array())
    {
        $PageImage = new PageImage($this->app);
        return $PageImage->page_image($page_id, $options);
    }
}
