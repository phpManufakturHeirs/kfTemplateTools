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

use Twig_Extension;
use Twig_SimpleFunction;
use Silex\Application;

class TwigExtension extends Twig_Extension
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
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'TemplateTools';
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getGlobals()
     */
    public function getGlobals()
    {
        return array(
            'CMS_ADDONS_PATH' => CMS_ADDONS_PATH,
            'CMS_ADDONS_URL' => CMS_ADDONS_URL,
            'CMS_ADMIN_PATH' => CMS_ADMIN_PATH,
            'CMS_ADMIN_URL' => CMS_ADMIN_URL,
            'CMS_ADDONS_PATH' => CMS_ADDONS_PATH,
            'CMS_DESCRIPTION' => CMS_DESCRIPTION,
            'CMS_KEYWORDS' => CMS_KEYWORDS,
            'CMS_LOCALE' => CMS_LOCALE,
            'CMS_LOGIN_ENABLED' => CMS_LOGIN_ENABLED,
            'CMS_LOGIN_FORGOTTEN_URL' => CMS_LOGIN_FORGOTTEN_URL,
            'CMS_LOGIN_REDIRECT_URL' => CMS_LOGIN_REDIRECT_URL,
            'CMS_LOGIN_SIGNUP_ENABLED' => CMS_LOGIN_SIGNUP_ENABLED,
            'CMS_LOGIN_SIGNUP_URL' => CMS_LOGIN_SIGNUP_URL,
            'CMS_LOGIN_URL' => CMS_LOGIN_URL,
            'CMS_LOGOUT_URL' => CMS_LOGOUT_URL,
            'CMS_MEDIA_PATH' => CMS_MEDIA_PATH,
            'CMS_MEDIA_URL' => CMS_MEDIA_URL,
            'CMS_PAGES_DIRECTORY' => CMS_PAGES_DIRECTORY,
            'CMS_PATH' => CMS_PATH,
            'CMS_SEARCH_VISIBILITY' => CMS_SEARCH_VISIBILITY,
            'CMS_TABLE_PREFIX' => CMS_TABLE_PREFIX,
            'CMS_TEMPLATES_PATH' => CMS_TEMPLATES_PATH,
            'CMS_TEMPLATES_URL' => CMS_TEMPLATES_URL,
            'CMS_TITLE' => CMS_TITLE,
            'CMS_TYPE' => CMS_TYPE,
            'CMS_URL' => CMS_URL,
            'CMS_USER_ACCOUNT_URL' => CMS_USER_ACCOUNT_URL,
            'CMS_USER_DISPLAYNAME' => CMS_USER_DISPLAYNAME,
            'CMS_USER_EMAIL' => CMS_USER_EMAIL,
            'CMS_USER_ID' => CMS_USER_ID,
            'CMS_USER_IS_AUTHENTICATED' => CMS_USER_IS_AUTHENTICATED,
            'CMS_USER_USERNAME' => CMS_USER_USERNAME,
            'CMS_VERSION' => CMS_VERSION,

            'EXTENSION_PATH' => EXTENSION_PATH,
            'EXTENSION_URL' => EXTENSION_URL,

            'FRAMEWORK_CACHE' => FRAMEWORK_CACHE,
            'FRAMEWORK_DEBUG' => FRAMEWORK_DEBUG,
            'FRAMEWORK_MEDIA_PATH' => FRAMEWORK_MEDIA_PATH,
            'FRAMEWORK_MEDIA_URL' => FRAMEWORK_MEDIA_URL,
            'FRAMEWORK_PATH' => FRAMEWORK_PATH,
            'FRAMEWORK_TABLE_PREFIX' => FRAMEWORK_TABLE_PREFIX,
            'FRAMEWORK_URL' => FRAMEWORK_URL,

            'HELPER_PATH' => HELPER_PATH,
            'HELPER_URL' => HELPER_URL,

            'LIBRARY_PATH' => LIBRARY_PATH,
            'LIBRARY_URL' => LIBRARY_URL,

            'MANUFAKTUR_PATH' => MANUFAKTUR_PATH,
            'MANUFAKTUR_URL' => MANUFAKTUR_URL,

            'PAGE_DESCRIPTION' => PAGE_DESCRIPTION,
            'PAGE_EXTENSION' => PAGE_EXTENSION,
            'PAGE_FOOTER' => PAGE_FOOTER,
            'PAGE_HAS_CHILD' => PAGE_HAS_CHILD,
            'PAGE_HEADER' => PAGE_HEADER,
            'PAGE_ID' => PAGE_ID,
            'PAGE_KEYWORDS' => PAGE_KEYWORDS,
            'PAGE_MENU_LEVEL' => PAGE_MENU_LEVEL,
            'PAGE_MENU_TITLE' => PAGE_MENU_TITLE,
            'PAGE_MODIFIED_BY' => PAGE_MODIFIED_BY,
            'PAGE_MODIFIED_WHEN' => PAGE_MODIFIED_WHEN,
            'PAGE_PARENT_ID' => PAGE_PARENT_ID,
            'PAGE_TITLE' => PAGE_TITLE,
            'PAGE_URL' => PAGE_URL,
            'PAGE_VISIBILITY' => PAGE_VISIBILITY,

            'POST_ID' => POST_ID,

            'SM2_ALL' => SM2_ALL,
            'SM2_ALLINFO' => SM2_ALLINFO,
            'SM2_ALLMENU' => SM2_ALLMENU,
            'SM2_BUFFER' => SM2_BUFFER,
            'SM2_COND_TERM' => SM2_COND_TERM,
            'SM2_CONDITIONAL' => SM2_CONDITIONAL,
            'SM2_CRUMB' => SM2_CRUMB,
            'SM2_CURR' => SM2_CURR,
            'SM2_CURRTREE' => SM2_CURRTREE,
            'SM2_ESCAPE' => SM2_ESCAPE,
            'SM2_MAX' => SM2_MAX,
            'SM2_NO_TITLE' => SM2_NO_TITLE,
            'SM2_NOCACHE' => SM2_NOCACHE,
            'SM2_NOESCAPE' => SM2_NOESCAPE,
            'SM2_NUMCLASS' => SM2_NUMCLASS,
            'SM2_PRETTY' => SM2_PRETTY,
            'SM2_ROOT' => SM2_ROOT,
            'SM2_SHOWHIDDEN' => SM2_SHOWHIDDEN,
            'SM2_SIBLING' => SM2_SIBLING,
            'SM2_START' => SM2_START,
            'SM2_TRIM' => SM2_TRIM,
            'SM2_XHTML_STRICT' => SM2_XHTML_STRICT,

            'TEMPLATE_DEFAULT_NAME' => TEMPLATE_DEFAULT_NAME,
            'TEMPLATE_DIRECTORY' => TEMPLATE_DIRECTORY,
            'TEMPLATE_NAME' => TEMPLATE_NAME,
            'TEMPLATE_PATH' => TEMPLATE_PATH,
            'TEMPLATE_URL' => TEMPLATE_URL,

            'THIRDPARTY_PATH' => THIRDPARTY_PATH,
            'THIRDPARTY_URL' => THIRDPARTY_URL,

            'TOPIC_ID' => TOPIC_ID,

        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            'ellipsis' => new \Twig_Filter_Method($this, 'Ellipsis'),
            'markdown' => new \Twig_Filter_Method($this, 'MarkdownHTML'),
            'humanize' => new \Twig_Filter_Method($this, 'Humanize'),
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'bootstrap_nav' => new \Twig_Function_Method($this, 'BootstrapNav'),
            'bootstrap_breadcrumb' => new \Twig_Function_Method($this, 'BootstrapBreadcrumb'),
            'bootstrap_pager' => new \Twig_Function_Method($this, 'BootstrapPager'),
            'classic_breadcrumb' => new \Twig_Function_Method($this, 'ClassicBreadcrumb'),
            'classic_pager' => new \Twig_Function_Method($this, 'ClassicPager'),
            'command' => new \Twig_Function_Method($this, 'kitCommand'),
            'droplet' => new \Twig_Function_Method($this, 'Droplet'),
            'ellipsis' => new \Twig_Function_Method($this, 'Ellipsis'),
            'humanize' => new \Twig_Function_Method($this, 'Humanize'),
            'image' => new \Twig_Function_Method($this, 'Image'),
            'markdown' => new \Twig_Function_Method($this, 'MarkdownHTML'),
            'markdown_file' => new \Twig_Function_Method($this, 'MarkdownFile'),
            'page_content' => new \Twig_Function_Method($this, 'PageContent'),
            'page_description' => new \Twig_Function_Method($this, 'PageDescription'),
            'page_next_id' => new \Twig_Function_Method($this, 'PageNextID'),
            'page_previous_id' => new \Twig_Function_Method($this, 'PagePreviousID'),
            'page_title' => new \Twig_Function_Method($this, 'PageTitle'),
            'page_url' => new \Twig_Function_Method($this, 'PageURL'),
            'register_frontend_modfiles' => new \Twig_Function_Method($this, 'RegisterFrontendModfiles'),
            'register_frontend_modfiles_body' => new \Twig_Function_Method($this, 'RegisterFrontendModfilesBody'),
            'show_menu2' => new \Twig_Function_Method($this, 'ShowMenu2'),

        );
    }

    /**
     * Ellipsis function - shorten the given $text to $length at the nearest
     * space and add three dots at the end ...
     *
     * @param string $text
     * @param number $length
     * @param boolean $striptags remove HTML tags by default
     * @param boolean $htmlpurifier use HTML Purifier (false by default, ignored if striptags=true)
     * @return string
     */
    public function Ellipsis($text, $length=100, $striptags=true, $htmlpurifier=false)
    {
        return $this->app['tools']->ellipsis($text, $length, $striptags, $htmlpurifier, false);
    }

    /**
     * Return a array with the URL source, width and height of the given image.
     * If $max_width or $max_height ar not NULL a new image will be resampled.
     *
     * @param string $relative_image_path relative path to $parent_path
     * @param integer $max_width of the image in pixel
     * @param integer $max_height of the image in pixel
     * @param string $parent_path FRAMEWORK_PATH by default
     * @param string $parent_url FRAMEWORK_URL by default
     * @param boolean $cache by default cache the file
     * @return array with src, width, height and path
     */
    public function Image($relative_image_path, $max_width=null, $max_height=null, $parent_path=FRAMEWORK_PATH, $parent_url=FRAMEWORK_URL, $cache=true)
    {
        $relative_image_path = $this->app['tools']->sanitizePath($relative_image_path);
        if ($relative_image_path[0] != '/') {
            $relative_image_path = '/'.$relative_image_path;
        }

        $parent_path = $this->app['tools']->sanitizePath($parent_path);

        if ($parent_url[strlen($parent_url)-1] == '/') {
            $parent_url = substr($parent_url, 0, -1);
        }

        if (!$this->app['filesystem']->exists($parent_path.$relative_image_path)) {
            $this->app['monolog']->addDebug("The image $parent_path.$relative_image_path does not exists!",
                array(__METHOD__, __LINE__));
            return array(
                'src' => $parent_url.$relative_image_path,
                'width' => '100%',
                'height' => '100%'
            );
        }

        $image_info = $this->app['image']->getImageInfo($parent_path.$relative_image_path);

        if ((!is_null($max_width) && ($image_info['width'] > $max_width)) ||
            (!is_null($max_height) && ($image_info['height'] > $max_height))) {

            // optimize the image
            $new_size = $this->app['image']->reCalculateImage($image_info['width'], $image_info['height'], $max_width, $max_height);

            // create a new filename
            $pathinfo = pathinfo($relative_image_path);

            $new_relative_image_path = sprintf('%s/%s_%dx%d.%s', $pathinfo['dirname'],
                $pathinfo['filename'], $new_size['width'], $new_size['height'], $pathinfo['extension']);

            $tweak_path = FRAMEWORK_PATH.'/media/twig';
            $tweak_url = FRAMEWORK_URL.'/media/twig';

            if (!$cache || !$this->app['filesystem']->exists($tweak_path.$new_relative_image_path) ||
                (filemtime($tweak_path.$new_relative_image_path) != $image_info['last_modified'])) {
                    // create a resampled image
                    $this->app['image']->resampleImage($parent_path.$relative_image_path, $image_info['image_type'],
                        $image_info['width'], $image_info['height'], $tweak_path.$new_relative_image_path,
                        $new_size['width'], $new_size['height']);
                }

                return array(
                    'path' => $tweak_path.$new_relative_image_path,
                    'src' => $tweak_url.$new_relative_image_path,
                    'width' => $new_size['width'],
                    'height' => $new_size['height']
                );
        }
        else {
            // nothing to do ...
            return array(
                'path' => $parent_path.$relative_image_path,
                'src' => $parent_url.$relative_image_path,
                'width' => $image_info['width'],
                'height' => $image_info['height']
            );
        }
    }

    /**
     * Return the given markdown $text as HTML
     *
     * @param string $text
     * @param boolean $extra
     */
    public function MarkdownHTML($text, $extra=true)
    {
        return $this->app['markdown']->html($text, $extra, false);
    }

    /**
     * Read the given Markdown file and return the content as HTML
     *
     * @param string $path
     * @param string $extra
     */
    public function MarkdownFile($path, $extra=true)
    {
        return $this->app['markdown']->file($path, $extra, false);
    }

    /**
     * Return the page content for the given block
     *
     * @param number $block
     */
    public function PageContent($block=1)
    {
        return $this->app['cms']->page_content($block, false);
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
     * @return Ambigous <boolean, string, unknown>
     */
    public function ShowMenu2(
        $aMenu          = 0,
        $aStart         = SM2_ROOT,
        $aMaxLevel      = -1999, // SM2_CURR+1
        $aOptions       = SM2_TRIM,
        $aItemOpen      = false,
        $aItemClose     = false,
        $aMenuOpen      = false,
        $aMenuClose     = false,
        $aTopItemOpen   = false,
        $aTopMenuOpen   = false)
    {
        return $this->app['cms']->show_menu2($aMenu,$aStart,$aMaxLevel,$aOptions,$aItemOpen,
            $aItemClose,$aMenuOpen,$aMenuClose,$aTopItemOpen,$aTopMenuOpen, false);
    }

    /**
     * Execute a Droplet
     *
     * @param string $droplet
     * @param array $parameter
     * @return string
     */
    public function Droplet($droplet, $parameter=array())
    {
        return $this->app['droplet']->execute($droplet, $parameter, false);
    }

    /**
     * Execute the given kitCommand
     *
     * @param string $command
     * @param parameter $parameter
     * @return string
     */
    public function kitCommand($command, $parameter=array())
    {
        return $this->app['command']->execute($command, $parameter, false);
    }

    /**
     * Function to add optional module Javascript or CSS stylesheets into the
     * <head> section of the frontend
     *
     * @param string $file_type
     * @return string
     */
    public function RegisterFrontendModfiles($file_type='css')
    {
        return $this->app['cms']->register_frontend_modfiles($file_type, false);
    }

    /**
     * Function to add optional module Javascript into the <body> section
     * of the frontend
     *
     * @param string $file_type
     * @return string
     */
    public function RegisterFrontendModfilesBody($file_type='css')
    {
        return $this->app['cms']->register_frontend_modfiles_body($file_type, false);
    }

    /**
     * Return the page description for the actual PAGE_ID.
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the description for TOPICS oder NEWS
     *
     * @param array $arguments
     * @return string
     */
    public function PageDescription($arguments=null)
    {
        return $this->app['cms']->page_description($arguments, false);
    }

    /**
     * Return the page title for the actual PAGE_ID
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the title for TOPICS oder NEWS
     *
     * @param array $arguments
     * @param string $spacer
     * @param string $template
     * @return string
     */
    public function PageTitle($arguments=null, $spacer= ' - ', $template='[PAGE_TITLE]')
    {
        return $this->app['cms']->page_title($arguments, false, $spacer, $template);
    }

    /**
     * Return the page keywords for the actual PAGE_ID
     * If $arguments is an array and key = 'topic_id' or 'post_id' and value > 0
     * the function return the keywords for TOPICS oder NEWS
     *
     * @param boolean $prompt
     * @return string
     */
    public function PageKeywords($arguments=null)
    {
        return $this->app['cms']->page_keywords($arguments, false);
    }

    /**
     * Makes a technical name human readable.
     *
     * Sequences of underscores are replaced by single spaces. The first letter
     * of the resulting string is capitalized, while all other letters are
     * turned to lowercase.
     *
     * @param string $text The text to humanize.
     * @param boolean $prompt
     * @return string The humanized text.
     */
    public function Humanize($text, $prompt=true)
    {
        return $this->app['tools']->humanize($text, $prompt);
    }

    /**
     * Create a unsorted list for the Bootstrap nav components
     *
     * @param string $class
     * @param array $options
     * @return string
     */
    public function BootstrapNav($class, $options=array())
    {
        return $this->app['bootstrap']->nav($class, $options, false);
    }

    /**
     * Create a Bootstrap breadcrumb navigation
     *
     * @param array $options
     * @return string breadcrumb
     */
    public function BootstrapBreadcrumb($options=array())
    {
        return $this->app['bootstrap']->breadcrumb($options, false);
    }

    /**
     * Create a Classic breadcrumb navigation
     *
     * @param array $options
     * @return string breadcrumb
     */
    public function ClassicBreadcrumb($options=array())
    {
        return $this->app['classic']->breadcrumb($options, false);
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
    public function PageURL($page_id=PAGE_ID, $arguments=null)
    {
        return $this->app['cms']->page_url($page_id, $arguments, false);
    }

    /**
     * Get the next page ID for the given page ID
     *
     * @param integer $page_id
     * @param array $page_visibility
     * @return integer
     */
    public function PageNextID($page_id=PAGE_ID, $visibility=array('public'))
    {
        return $this->app['cms']->page_next_id($page_id, $visibility, false);
    }

    /**
     * Get the previous page ID for the given page ID
     *
     * @param integer $page_id
     * @param array $page_visibility
     * @param boolean $prompt
     * @return integer
     */
    public function PagePreviousID($page_id=PAGE_ID, $visibility=array('public'))
    {
        return $this->app['cms']->page_previous_id($page_id, $visibility, false);
    }

    /**
     * Create a Bootstrap Pager to step through the site
     *
     * @param array $options
     * @return string
     */
    public function BootstrapPager($options=array())
    {
        return $this->app['bootstrap']->pager($options, false);
    }

    /**
     * Create a Pager to step through the site
     *
     * @param array $options
     * @return string
     */
    public function ClassicPager($options=array())
    {
        return $this->app['classic']->pager($options, false);
    }
}
