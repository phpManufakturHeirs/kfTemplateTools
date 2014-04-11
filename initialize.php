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
if (!defined('CMS_LOCALE')) define('CMS_LOCALE', strtolower(LANGUAGE));
if (!defined('CMS_TEMPLATES_PATH')) define('CMS_TEMPLATES_PATH', CMS_PATH.'/templates');
if (!defined('CMS_TEMPLATES_URL')) define('CMS_TEMPLATES_URL', CMS_URL.'/templates');
if (!defined('CMS_ADDONS_PATH')) define('CMS_ADDONS_PATH', CMS_PATH.'/modules');
if (!defined('CMS_ADDONS_URL')) define('CMS_ADDONS_URL', CMS_URL.'/modules');
if (!defined('CMS_USER_ID')) define('CMS_USER_ID', isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : -1);
if (!defined('CMS_USER_USERNAME')) define('CMS_USER_USERNAME', isset($_SESSION['USERNAME']) ? $_SESSION['USERNAME'] : 'anonymous');
if (!defined('CMS_USER_DISPLAYNAME')) define('CMS_USER_DISPLAYNAME', isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : 'anonymous');
if (!defined('CMS_USER_EMAIL')) define('CMS_USER_EMAIL', isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : '');
if (!defined('CMS_USER_IS_AUTHENTICATED')) define('CMS_USER_IS_AUTHENTICATED', ((CMS_USER_ID > 0) && (CMS_USER_EMAIL != '')));
if (!defined('CMS_USER_ACCOUNT_URL')) define('CMS_USER_ACCOUNT_URL', PREFERENCES_URL);
if (!defined('CMS_LOGIN_ENABLED')) define('CMS_LOGIN_ENABLED', FRONTEND_LOGIN);
if (!defined('CMS_LOGIN_URL')) define('CMS_LOGIN_URL', LOGIN_URL);
if (!defined('CMS_LOGIN_FORGOTTEN_URL')) define('CMS_LOGIN_FORGOTTEN_URL', FORGOT_URL);
// get the redirect URL for the login
$redirect_url = ((isset($_SESSION['HTTP_REFERER']) && $_SESSION['HTTP_REFERER'] != '') ? $_SESSION['HTTP_REFERER'] : CMS_URL);
$redirect_url = (isset($_REQUEST['redirect']) && !empty($_REQUEST['redirect'])) ? $_REQUEST['redirect'] : $redirect_url;
if (!defined('CMS_LOGIN_REDIRECT_URL')) define('CMS_LOGIN_REDIRECT_URL', $redirect_url);
if (!defined('CMS_LOGIN_SIGNUP_ENABLED')) define('CMS_LOGIN_SIGNUP_ENABLED', FRONTEND_SIGNUP);
if (!defined('CMS_LOGIN_SIGNUP_URL')) define('CMS_LOGIN_SIGNUP_URL', SIGNUP_URL);
if (!defined('CMS_LOGOUT_URL')) define('CMS_LOGOUT_URL', LOGOUT_URL);

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
if (!defined('PAGE_VISIBILITY')) define('PAGE_VISIBILITY', VISIBILITY);
if (!defined('PAGE_HEADER')) define('PAGE_HEADER', $tools['cms']->page_header(false));
if (!defined('PAGE_FOOTER')) define('PAGE_FOOTER', $tools['cms']->page_footer('Y', false));

try {
    // get PAGE_MODIFIED_WHEN and PAGE_MODIFIED_BY
    $SQL = "SELECT `modified_when`, `display_name` FROM `".CMS_TABLE_PREFIX."pages`, `".CMS_TABLE_PREFIX."users` ".
        "WHERE `user_id`=`modified_by` AND `page_id`=".PAGE_ID;
    $result = $tools['db']->fetchAssoc($SQL);
    if (!isset($result['modified_when'])) {
        throw new \Exception("Can't read the page information for ID ".PAGE_ID." from the database!");
    }
    if (!defined('PAGE_MODIFIED_WHEN')) define('PAGE_MODIFIED_WHEN', date('Y-m-d H:i:s', $result['modified_when']));
    if (!defined('PAGE_MODIFIED_BY')) define('PAGE_MODIFIED_BY', $result['display_name']);
} catch (\Doctrine\DBAL\DBALException $e) {
    throw new \Exception($e);
}

if (!defined('TEMPLATE_PATH')) define('TEMPLATE_PATH', CMS_PATH.substr(TEMPLATE_DIR, strlen(CMS_URL)));
if (!defined('TEMPLATE_URL')) define('TEMPLATE_URL', TEMPLATE_DIR);
if (!defined('TEMPLATE_DIRECTORY')) define('TEMPLATE_DIRECTORY', substr(TEMPLATE_DIR, strlen(CMS_TEMPLATES_URL)+1));

try {
    // get extended template information
    $SQL = "SELECT `name`, `description`, `version` FROM `".CMS_TABLE_PREFIX."addons` WHERE `type`='template' AND `directory`='".TEMPLATE_DIRECTORY."'";
    $result = $tools['db']->fetchAssoc($SQL);
    if (!isset($result['name'])) {
        throw new \Exception("Can't read the template information for ".TEMPLATE_DIRECTORY." from the database!");
    }
    if (!defined('TEMPLATE_NAME')) define('TEMPLATE_NAME', $result['name']);
    if (!defined('TEMPLATE_DESCRIPTION')) define('TEMPLATE_DESCRIPTION', $result['description']);
    if (!defined('TEMPLATE_VERSION')) define('TEMPLATE_VERSION', $result['version']);
} catch (\Doctrine\DBAL\DBALException $e) {
    throw new \Exception($e);
}

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
$tools['translator']->setLocale(CMS_LOCALE);

// load the language files for the TemplateTools
$tools['utils']->addLanguageFiles(MANUFAKTUR_PATH.'/TemplateTools/Data/Locale');
$tools['utils']->addLanguageFiles(MANUFAKTUR_PATH.'/TemplateTools/Data/Locale/Custom');

// load the metric language file from BASIC
$tools['utils']->addLanguageFiles(MANUFAKTUR_PATH.'/Basic/Data/Locale/Metric');

if ($tools['filesystem']->exists(TEMPLATE_PATH.'/locale')) {
    // if the template has a /locale directory load these language files also
    $tools['utils']->addLanguageFiles(TEMPLATE_PATH.'/locale');
}

// register Twig
$tools->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(
        TEMPLATE_PATH
        ),
    'twig.options' => array(
        'cache' => FRAMEWORK_CACHE ? FRAMEWORK_PATH . '/temp/cache/' : false,
        'strict_variables' => FRAMEWORK_DEBUG,
        'debug' => FRAMEWORK_DEBUG ? true : false,
        'autoescape' => false
    )
));

// add namespaces for easy template access
$tools['twig.loader.filesystem']->addPath(MANUFAKTUR_PATH, 'phpManufaktur');
$tools['twig.loader.filesystem']->addPath(THIRDPARTY_PATH, 'thirdParty');
$tools['twig.loader.filesystem']->addPath(CMS_TEMPLATES_PATH, 'Templates');
$tools['twig.loader.filesystem']->addPath(MANUFAKTUR_PATH.'/TemplateTools/Template', 'TemplateTools');

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
$tools['kitcommand'] = $tools->share(function($tools) {
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
