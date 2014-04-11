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


class cmsFunctions
{
    protected $app = null;
    protected $PageData = null;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->PageData = new Page($app);
    }

    /**
     * Return the page description for the actual PAGE_ID
     *
     * @return string
     */
    public function page_description()
    {
        return \page_description();
    }

    /**
     * Return the page title for the actual PAGE_ID
     *
     * @param string $spacer
     * @param string $template
     * @return string
     */
    public function page_title($spacer=' - ', $template='[PAGE_TITLE]')
    {
        return \page_title($spacer, $template);
    }

    /**
     * Return the page keywords for the actual PAGE_ID
     *
     * @return string
     */
    public function page_keywords()
    {
        return \page_keywords();
    }

    /**
     * Return the page content by the given block for the actual PAGE_ID
     *
     * @param number $block
     * @return string
     */
    public function page_content($block=1)
    {
        return \page_content($block);
    }

    /**
     * Mapping the show_menu2()
     *
     * @param number $aMenu
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

    /**
     * Get the URL of the given page ID. If arguments 'topic_id' or 'post_id'
     * the function will return the URL for the given TOPICS or NEWS article
     *
     * @param integer $page_id
     * @param null|array $arguments
     * @throws \Exception
     * @return string URL of the page
     */
    public function page_url($page_id, $arguments=null)
    {
        return $this->PageData->getURL($page_id, $arguments);
    }

    /**
     * Return the page footer from the CMS options.
     * You can use the placeholders [YEAR] and [PROCESS_TIME]
     *
     * @param string $date_format for the [YEAR] placeholder
     * @return string formatted
     */
    public function page_footer($date_format='Y', $prompt=true)
    {
        if ($prompt) {
            \page_footer($date_format);
        }
        else {
            ob_start();
            \page_footer($date_format);
            return ob_get_clean();
        }
    }

    /**
     * Return the page header from the CMS options.
     *
     * @param string $date_format (not supported)
     */
    public function page_header($prompt=true)
    {
        if ($prompt) {
            \page_header();
        }
        else {
            ob_start();
            \page_header();
            return ob_get_clean();
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
        if (!function_exists('register_frontend_modfiles')) {
            // i.e. BlackCat does not support this function!
            return '';
        }
        if ($prompt) {
            \register_frontend_modfiles($file_type);
        }
        else {
            ob_start();
            \register_frontend_modfiles($file_type);
            return ob_get_clean();
        }
    }

    /**
     * Function to add optional module Javascript into the <body> section
     * of the frontend
     *
     * @param string $file_type
     * @param boolean $prompt
     * @return string
     */
    public function register_frontend_modfiles_body($file_type='js', $prompt=true)
    {
        if (!function_exists('register_frontend_modfiles_body')) {
            // i.e. BlackCat does not support this function!
            return '';
        }
        if ($prompt) {
            \register_frontend_modfiles_body($file_type);
        }
        else {
            ob_start();
            \register_frontend_modfiles_body($file_type);
            return ob_get_clean();
        }
    }
}
