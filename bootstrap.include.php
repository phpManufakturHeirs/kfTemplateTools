<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

// execute the setup
$admin->get('/templatetools/setup',
    'phpManufaktur\TemplateTools\Data\Setup\Setup::exec');
$admin->get('/templatetools/update',
    'phpManufaktur\TemplateTools\Data\Setup\Update::exec');
$admin->get('/templatetools/uninstall',
    'phpManufaktur\TemplateTools\Data\Setup\Uninstall::exec');