<?php

/**
 * TemplateTools
 *
 * @author Team phpManufaktur <team@phpmanufaktur.de>
 * @link https://kit2.phpmanufaktur.de
 * @copyright 2014 Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

if ('á' != "\xc3\xa1") {
    // the language files must be saved as UTF-8 (without BOM)
    throw new \Exception('The language file ' . __FILE__ . ' is damaged, it must be saved UTF-8 encoded!');
}

return array(
    'A error occured while executing the Droplet, please check the PHP code.'
        => 'Bei der Ausführung des Droplet trat ein Fehler auf, bitte prüfen Sie den PHP Code.',
    'Authenticated'
        => 'Angemeldet',

    'Enter password'
        => 'Passwort',
    'Enter username'
        => 'Benutzername',

    'Forgot your password?'
        => 'Haben Sie Ihr Passwort vergessen?',

    'I want to signup!'
        => 'Ich möchte mich registrieren',

    'Login'
        => 'Anmelden',
    'Logout'
        => 'Abmelden',

    'Password'
        => 'Passwort',

    'The Droplet %droplet% does not exists!'
        => 'Das Droplet <i>%droplet%</i> existiert nicht!',

    'User account'
        => 'Benutzerkonto',
    'Username'
        => 'Benutzername',

    'Welcome back, %name%'
        => 'Herzlich willkommen, %name%!',
);
