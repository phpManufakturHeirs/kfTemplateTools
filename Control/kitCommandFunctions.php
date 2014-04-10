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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class kitCommandFunctions
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function execute($command, $parameter=array(), $prompt=true)
    {
        $params = array(
            'cms' => array(
                'locale' => CMS_LANGUAGE,
                'page_id' => PAGE_ID,
                'page_url' => PAGE_URL,
                'user' => array(
                    'id' => -1,
                    'name' => '',
                    'email' => ''
                ),
            ),
            'GET' => array(),
            'POST' => array(),
            'parameter' => $parameter,
        );

        $option = array(
            CURLOPT_URL => FRAMEWORK_URL.'/command/'.strtolower($command),
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'kitFramework:TemplateTools',
            CURLOPT_POSTFIELDS => $parameter,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch = curl_init();
        curl_setopt_array($ch, $option);

        if (false === ($response = curl_exec($ch))) {
            trigger_error(curl_error($ch), E_USER_ERROR);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if ($prompt) {
            echo $result;
        }
        else {
            return $result;
        }
        /*
        // process each kitCommand
        $subRequest = Request::create(FRAMEWORK_URL.'/command/'.strtolower($command), 'POST', $params);
        $Response = $this->app->handle($subRequest, HttpKernelInterface::MASTER_REQUEST, false);
        $result = $Response->getContent();
        if ($prompt) {
            echo $result;
        }
        else {
            return $result;
        }
        */
    }
}
