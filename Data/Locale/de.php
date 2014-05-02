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
    
    'Missing the message to alert!'
        => 'Es wurde keine Mitteilung übergeben, die gemeldet werden kann!',
    
    'Next'
        => 'Nächste',

    'Password'
        => 'Passwort',
    'Previous'
        => 'Vorherige',

    '<em>Ridiculously Responsive Social Sharing Buttons</em>, using about <strong>%percent%%</strong> width of the <em>Panel Vontainer</em>.'
        => '<em>Ridiculously Responsive Social Sharing Buttons</em>, verwenden etwa <strong>%percent%%</strong> der zur Verfügung stehenden Breite des <em>Panel Container</em>.',

    'Search'
        => 'Suche',

    'The Droplet %droplet% does not exists!'
        => 'Das Droplet <i>%droplet%</i> existiert nicht!',
    'The <em>%pattern_type% Pager</em> enable you to step forward and backward through the whole Website.'
        => 'Der <em>%pattern_type% Pager</em> ermöglicht es Ihnen sich vorwärts und rückwärts durch die gesamte Website zu bewegen.',

    'User account'
        => 'Benutzerkonto',
    'Username'
        => 'Benutzername',

    'Welcome back, %name%'
        => 'Herzlich willkommen, %name%!',
    'Welcome to the start page of %CMS_TITLE%!'
        => 'Herzlich willkommen auf der Startseite von %CMS_TITLE%!',
    
);
