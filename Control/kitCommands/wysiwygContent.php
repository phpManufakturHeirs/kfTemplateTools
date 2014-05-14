<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

namespace phpManufaktur\TemplateTools\Control\kitCommands;

use Silex\Application;
use phpManufaktur\Basic\Control\kitCommand\Basic;

class wysiwygContent extends Basic
{

    /**
     * Controller for the kitCommand wysiwyg_content.
     * Return the content of the given WYSIWYG Section
     *
     * @param Application $app
     * @throws \InvalidArgumentException
     * @return string
     */
    public function Controller(Application $app)
    {
        // initialize the Basic class
        $this->initParameters($app);
        // get the command parameters
        $parameter = $this->getCommandParameters();

        if (isset($parameter['section_id'])) {
            if (false === ($section_id = filter_var($parameter['section_id'], FILTER_VALIDATE_INT))) {
                throw new \InvalidArgumentException('The $section_id must be of type integer!');
            }
            $SQL = "SELECT `content` FROM `".CMS_TABLE_PREFIX."mod_wysiwyg` WHERE `section_id`=$section_id";
            $content = $this->app['db']->fetchColumn($SQL);
            $content = str_replace('{SYSVAR:MEDIA_REL}', CMS_MEDIA_URL, $content);
            $result = (!empty($content)) ? $this->app['utils']->unsanitizeText($content) : '';
            return $result;
        }
        // nothing to do ...
        return '';
    }
}
