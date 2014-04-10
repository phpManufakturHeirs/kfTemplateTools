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
            'ACTIVE_TEMPLATE_PATH' => ACTIVE_TEMPLATE_PATH,
            'ACTIVE_TEMPLATE_URL' => ACTIVE_TEMPLATE_URL,

            'CMS_ADDONS_URL' => CMS_ADDONS_URL,
            'CMS_LANGUAGE' => CMS_LANGUAGE,
            'CMS_ADMIN_PATH' => CMS_ADMIN_PATH,
            'CMS_ADMIN_URL' => CMS_ADMIN_URL,
            'CMS_ADDONS_PATH' => CMS_ADDONS_PATH,
            'CMS_MEDIA_PATH' => CMS_MEDIA_PATH,
            'CMS_MEDIA_URL' => CMS_MEDIA_URL,
            'CMS_PATH' => CMS_PATH,
            'CMS_TABLE_PREFIX' => CMS_TABLE_PREFIX,
            'CMS_TEMPLATE_PATH' => CMS_TEMPLATE_PATH,
            'CMS_TEMPLATE_URL' => CMS_TEMPLATE_URL,
            'CMS_TYPE' => CMS_TYPE,
            'CMS_URL' => CMS_URL,
            'CMS_USER_DISPLAYNAME' => CMS_USER_DISPLAYNAME,
            'CMS_USER_EMAIL' => CMS_USER_EMAIL,
            'CMS_USER_ID' => CMS_USER_ID,
            'CMS_USER_IS_AUTHENTICATED' => CMS_USER_IS_AUTHENTICATED,
            'CMS_USER_USERNAME' => CMS_USER_USERNAME,
            'CMS_VERSION' => CMS_VERSION,

            'EXTENSION_PATH' => EXTENSION_PATH,
            'EXTENSION_URL' => EXTENSION_URL,

            'FRAMEWORK_PATH' => FRAMEWORK_PATH,
            'FRAMEWORK_URL' => FRAMEWORK_URL,
            'FRAMEWORK_MEDIA_PATH' => FRAMEWORK_MEDIA_PATH,
            'FRAMEWORK_MEDIA_URL' => FRAMEWORK_MEDIA_URL,
            'FRAMEWORK_TABLE_PREFIX' => FRAMEWORK_TABLE_PREFIX,

            'HELPER_PATH' => HELPER_PATH,
            'HELPER_URL' => HELPER_URL,

            'LIBRARY_PATH' => LIBRARY_PATH,
            'LIBRARY_URL' => LIBRARY_URL,

            'PAGE_DESCRIPTION' => PAGE_DESCRIPTION,
            'PAGE_ID' => PAGE_ID,
            'PAGE_KEYWORDS' => PAGE_KEYWORDS,
            'PAGE_TITLE' => PAGE_TITLE,
            'PAGE_URL' => PAGE_URL,

            'THIRDPARTY_PATH' => THIRDPARTY_PATH,
            'THIRDPARTY_URL' => THIRDPARTY_URL,

        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            'ellipsis' => new \Twig_Filter_Method($this, 'filterEllipsis'),
            'markdown' => new \Twig_Filter_Method($this, 'filterMarkdown')
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'image' => new \Twig_Function_Method($this, 'functionImage'),
            'markdown' => new \Twig_Function_Method($this, 'functionMarkdown'),
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
    public function filterEllipsis($text, $length=100, $striptags=true, $htmlpurifier=false)
    {
        return $this->app['utils']->Ellipsis($text, $length, $striptags, $htmlpurifier);
    }

    /**
     * Return the given markdown $text as HTML
     *
     * @param string $text
     * @param boolean $extra
     */
    public function filterMarkdown($text, $extra=true)
    {
        return $this->app['markdown']->html($text, $extra, false);
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
    public function functionImage($relative_image_path, $max_width=null, $max_height=null, $parent_path=FRAMEWORK_PATH, $parent_url=FRAMEWORK_URL, $cache=true)
    {
        $relative_image_path = $this->app['utils']->sanitizePath($relative_image_path);
        if ($relative_image_path[0] != '/') {
            $relative_image_path = '/'.$relative_image_path;
        }

        $parent_path = $this->app['utils']->sanitizePath($parent_path);

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
    public function functionMarkdown($text, $extra)
    {
        return $this->app['markdown']->html($text, $extra, false);
    }

}
