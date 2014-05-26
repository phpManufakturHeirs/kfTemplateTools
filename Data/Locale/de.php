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
    '- unknown -'
        => '- unbekannt -',

    'A error occured while executing the Droplet, please check the PHP code.'
        => 'Bei der Ausführung des Droplet trat ein Fehler auf, bitte prüfen Sie den PHP Code.',
    'Authenticated'
        => 'Angemeldet',

    '%direction% Page, Accesskey: [ALT]+%key%'
        => '%direction% Seite, Tastenkürzel: [ALT]+%key%',

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

    'Maintenance'
        => 'Wartungsarbeiten',
    'Missing the message to alert!'
        => 'Es wurde keine Mitteilung übergeben, die gemeldet werden kann!',

    'Next'
        => 'Nächste',

    'Password'
        => 'Passwort',
    'Previous'
        => 'Vorherige',

    'Search'
        => 'Suche',
    'Sorry, but this site is temporary offline due service operations.'
        => 'Entschuldigung, diese Website ist wegen Wartungsarbeiten vorübergehend offline',

    'The Droplet %droplet% does not exists!'
        => 'Das Droplet <i>%droplet%</i> existiert nicht!',
    'The <em>%pattern_type% Pager</em> enable you to step forward and backward through the whole Website.'
        => 'Der <em>%pattern_type% Pager</em> ermöglicht es Ihnen sich vorwärts und rückwärts durch die gesamte Website zu bewegen.',
    'The maintenance mode is active - non authenticated visitors can not see this content!'
        => 'Der Wartungsmodus ist aktiviert - nicht angemeldete Besucher können die Inhalte dieser Seite nicht sehen!',

    'User account'
        => 'Benutzerkonto',
    'Username'
        => 'Benutzername',

    'We will be back soon ...'
        => 'Wir sind in Kürze zurück ...',
    'Welcome back, %name%'
        => 'Herzlich willkommen, %name%!',

    'Your browser (%name% %version%) is <strong>out of date</strong>. It has known <strong>security flaws</strong> and may <strong>not display all features</strong> of this and other websites. <strong><a href="%update%">Please update your browser</a></strong>.'
      => 'Sie verwenden einen <strong>veralteten Browser</strong> (%name% %version%) mit <strong>Sicherheitsschwachstellen</strong> und <strong>können nicht alle Funktionen dieser Webseite nutzen</strong>. <strong><a href="%update%">Bitte aktualisieren Sie Ihren Browser</a></strong>.'

);
