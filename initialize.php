<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

if (!defined('WB_PATH')) {
    trigger_error('The TemplateTools expect to be executed within a WebsiteBaker, LEPTON CMS or BlackCat CMS template!', E_USER_ERROR);
}

require_once realpath(WB_PATH.'/kit2/framework/autoload.php');

use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Silex\Application;
use Symfony\Component\Filesystem\Filesystem;
use phpManufaktur\Basic\Control\Utils;
use phpManufaktur\TemplateTools\Control\cmsFunctions;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Bridge\Monolog\Logger;
use phpManufaktur\Basic\Control\MarkdownFunctions;
use phpManufaktur\TemplateTools\Control\TwigExtension;
use phpManufaktur\TemplateTools\Control\DropletFunctions;
use phpManufaktur\TemplateTools\Control\kitCommandFunctions;
use phpManufaktur\Basic\Control\Image;

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$tools = new Application();

if (!defined('BOOTSTRAP_PATH')) define('BOOTSTRAP_PATH', WB_PATH.'/kit2');

// register the Framework Utils
$tools['utils'] = $tools->share(function($tools) {
    return new Utils($tools);
});

// define needed constants
if (!defined('CMS_PATH')) define('CMS_PATH', $tools['utils']->sanitizePath(WB_PATH));
if (!defined('CMS_URL')) define('CMS_URL', WB_URL);
if (!defined('CMS_MEDIA_PATH')) define('CMS_MEDIA_PATH', CMS_PATH.MEDIA_DIRECTORY);
if (!defined('CMS_MEDIA_URL')) define('CMS_MEDIA_URL', CMS_URL.MEDIA_DIRECTORY);
if (!defined('CMS_LANGUAGE')) define('CMS_LANGUAGE', strtolower(LANGUAGE));
if (!defined('CMS_TEMPLATE_PATH')) define('CMS_TEMPLATE_PATH', CMS_PATH.'/templates');
if (!defined('CMS_TEMPLATE_URL')) define('CMS_TEMPLATE_URL', CMS_URL.'/templates');
if (!defined('CMS_ADDONS_PATH')) define('CMS_ADDONS_PATH', CMS_PATH.'/modules');
if (!defined('CMS_ADDONS_URL')) define('CMS_ADDONS_URL', CMS_URL.'/modules');
if (!defined('CMS_USER_ID')) define('CMS_USER_ID', isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : -1);
if (!defined('CMS_USER_USERNAME')) define('CMS_USER_USERNAME', isset($_SESSION['USERNAME']) ? $_SESSION['USERNAME'] : 'anonymous');
if (!defined('CMS_USER_DISPLAYNAME')) define('CMS_USER_DISPLAYNAME', isset($_SESSION['DISPLAYNAME']) ? $_SESSION['DISPLAYNAME'] : 'anonymous');
if (!defined('CMS_USER_EMAIL')) define('CMS_USER_EMAIL', isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : '');
if (!defined('CMS_USER_IS_AUTHENTICATED')) define('CMS_USER_IS_AUTHENTICATED', ((CMS_USER_ID > 0) && (CMS_USER_EMAIL != '')));

// check for the framework configuration file
$framework_config = $tools['utils']->readConfiguration(realpath(BOOTSTRAP_PATH . '/config/framework.json'));

if (!defined('FRAMEWORK_DEBUG')) define('FRAMEWORK_DEBUG', (isset($framework_config['DEBUG'])) ? $framework_config['DEBUG'] : false);
$app['debug'] = FRAMEWORK_DEBUG;

if (!defined('FRAMEWORK_CACHE')) define('FRAMEWORK_CACHE', (isset($framework_config['CACHE'])) ? $framework_config['CACHE'] : true);

if (!defined('FRAMEWORK_PATH')) define('FRAMEWORK_PATH', CMS_PATH.'/kit2');
if (!defined('FRAMEWORK_URL')) define('FRAMEWORK_URL', CMS_URL.'/kit2');
if (!defined('FRAMEWORK_MEDIA_PATH')) define('FRAMEWORK_MEDIA_PATH', FRAMEWORK_PATH.'/media/public');
if (!defined('FRAMEWORK_MEDIA_URL')) define('FRAMEWORK_MEDIA_URL', FRAMEWORK_URL.'/media/public');

if (!defined('MANUFAKTUR_PATH')) define('MANUFAKTUR_PATH', FRAMEWORK_PATH . '/extension/phpmanufaktur/phpManufaktur');
if (!defined('MANUFAKTUR_URL')) define('MANUFAKTUR_URL', FRAMEWORK_URL . '/extension/phpmanufaktur/phpManufaktur');

if (!defined('THIRDPARTY_PATH')) define('THIRDPARTY_PATH', FRAMEWORK_PATH . '/extension/thirdparty/thirdParty');
if (!defined('THIRDPARTY_URL')) define('THIRDPARTY_URL', FRAMEWORK_URL . '/extension/thirdparty/thirdParty');

if (!defined('LIBRARY_PATH')) define('LIBRARY_PATH', MANUFAKTUR_PATH.'/Library/Library');
if (!defined('LIBRARY_URL')) define('LIBRARY_URL', MANUFAKTUR_URL.'/Library/Library');

if (!defined('EXTENSION_PATH')) define('EXTENSION_PATH', MANUFAKTUR_PATH.'/Library/Extension');
if (!defined('EXTENSION_URL')) define('EXTENSION_URL', MANUFAKTUR_URL.'/Library/Extension');

if (!defined('HELPER_PATH')) define('HELPER_PATH', MANUFAKTUR_PATH.'/Library/Helper');
if (!defined('HELPER_URL')) define('HELPER_URL', MANUFAKTUR_URL.'/Library/Helper');

if (!defined('ACTIVE_TEMPLATE_PATH')) define('ACTIVE_TEMPLATE_PATH', CMS_PATH.substr(TEMPLATE_DIR, strlen(CMS_URL)));
if (!defined('ACTIVE_TEMPLATE_URL')) define('ACTIVE_TEMPLATE_URL', TEMPLATE_DIR);

// get the filesystem into the application
$tools['filesystem'] = function() {
    return new Filesystem();
};

// register monolog
$tools->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => FRAMEWORK_PATH.'/logfile/templatetools.log',
    'monolog.level' => FRAMEWORK_DEBUG ? Logger::DEBUG : Logger::WARNING,
    'monolog.name' => 'TemplateTools',
    'monolog.maxfiles' => isset($framework_config['LOGFILE_ROTATE_MAXFILES']) ? $framework_config['LOGFILE_ROTATE_MAXFILES'] : 10
));
$tools['monolog']->popHandler();
$tools['monolog']->pushHandler(new Monolog\Handler\RotatingFileHandler(
    $tools['monolog.logfile'],
    $tools['monolog.maxfiles'],
    $tools['monolog.level'],
    false
));
$tools['monolog']->addDebug('Monolog initialized.');

// read the CMS configuration
$cms_config = $tools['utils']->readConfiguration(FRAMEWORK_PATH . '/config/cms.json');

if (!defined('CMS_ADMIN_PATH')) define('CMS_ADMIN_PATH', $tools['utils']->sanitizePath($cms_config['CMS_ADMIN_PATH']));
if (!defined('CMS_ADMIN_URL')) define('CMS_ADMIN_URL', $cms_config['CMS_ADMIN_URL']);
if (!defined('CMS_TYPE')) define('CMS_TYPE', $cms_config['CMS_TYPE']);
if (!defined('CMS_VERSION')) define('CMS_VERSION', $cms_config['CMS_VERSION']);

$tools['cms'] = $tools->share(function($tools) {
    return new cmsFunctions($tools);
});

// read the doctrine configuration
$doctrine_config = $tools['utils']->readConfiguration(FRAMEWORK_PATH.'/config/doctrine.cms.json');

if (!defined('CMS_TABLE_PREFIX')) define('CMS_TABLE_PREFIX', $doctrine_config['TABLE_PREFIX']);
if (!defined('FRAMEWORK_TABLE_PREFIX')) define('FRAMEWORK_TABLE_PREFIX', $doctrine_config['TABLE_PREFIX'] . 'kit2_');

$tools->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => $doctrine_config['DB_NAME'],
        'user' => $doctrine_config['DB_USERNAME'],
        'password' => $doctrine_config['DB_PASSWORD'],
        'host' => $doctrine_config['DB_HOST'],
        'port' => $doctrine_config['DB_PORT']
    )
));


if (!defined('PAGE_URL')) define('PAGE_URL', $tools['cms']->page_url(PAGE_ID));
if (!defined('PAGE_TITLE')) define('PAGE_TITLE', $tools['cms']->page_title());
if (!defined('PAGE_DESCRIPTION')) define('PAGE_DESCRIPTION', $tools['cms']->page_description());
if (!defined('PAGE_KEYWORDS')) define('PAGE_KEYWORDS', $tools['cms']->page_keywords());

// register the Translator
$tools->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en'
));
$tools['translator'] = $tools->share($tools->extend('translator', function($translator, $tools)
{
    $translator->addLoader('array', new ArrayLoader());
    return $translator;
}));

// set the locale from the CMS
$tools['translator']->setLocale(CMS_LANGUAGE);

// load the language files for the TemplateTools
$tools['utils']->addLanguageFiles(MANUFAKTUR_PATH.'/TemplateTools/Data/Locale');
$tools['utils']->addLanguageFiles(MANUFAKTUR_PATH.'/TemplateTools/Data/Locale/Custom');

if ($tools['filesystem']->exists(ACTIVE_TEMPLATE_PATH.'/locale')) {
    // if the template has a /locale directory load these language files also
    $tools['utils']->addLanguageFiles(ACTIVE_TEMPLATE_PATH.'/locale');
}

// register Twig
$tools->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(
        ACTIVE_TEMPLATE_PATH,
        CMS_TEMPLATE_PATH),
    'twig.options' => array(
        'cache' => FRAMEWORK_CACHE ? FRAMEWORK_PATH . '/temp/cache/' : false,
        'strict_variables' => FRAMEWORK_DEBUG,
        'debug' => FRAMEWORK_DEBUG ? true : false,
        'autoescape' => false
    )
));


$tools['twig'] = $tools->share($tools->extend('twig', function($twig, $tools)
{
    // add global variables, functions etc. for the templates
    $twig->addExtension(new TwigExtension($tools));
    if ($tools['debug']) {
        $twig->addExtension(new Twig_Extension_Debug());
    }
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
}));

$tools['monolog']->addDebug('TwigServiceProvider registered.');

// Markdown Parser
$tools['markdown'] = $tools->share(function($tools) {
    return new MarkdownFunctions($tools);
});

// execute droplets
$tools['droplet'] = $tools->share(function($tools) {
    return new DropletFunctions($tools);
});

// execute kitCommands
$tools['command'] = $tools->share(function($tools) {
    return new kitCommandFunctions($tools);
});

// image tools
$tools['image'] = $tools->share(function($tools) {
    return new Image($tools);
});


if (FRAMEWORK_CACHE) {
    // register the HTTP Cache Service
    $tools->register(new Silex\Provider\HttpCacheServiceProvider(), array(
        'http_cache.cache_dir' => FRAMEWORK_PATH . '/temp/cache/'
    ));
}
