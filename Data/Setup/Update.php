<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

namespace phpManufaktur\TemplateTools\Data\Setup;

use Silex\Application;

class Update
{
    protected $app = null;
    
    /**
     * Execute the Update for the TemplateTools
     * 
     * @param Application $app
     */
    public function exec(Application $app)
    {
        $this->app = $app;
        
        $Setup = new Setup($app);
        $Setup->install_templates();
        
        return $app['translator']->trans('Successfull updated the extension %extension%.',
            array('%extension%' => 'TemplateTools'));
    }
}