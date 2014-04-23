<?php
/**
 * Ceci est le boostrap d'ocramius que nous testons au sein de notre Album
 * Poir vérifier si l'architecture de notre projet est faisable plus facilement;
 * 
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 */
 
chdir(__DIR__);
 
$previousDir = '.';
 
while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());
 
    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php":'
                . ' is the Content module in a sub-directory of your application skeleton?'
        );
    }
 
    $previousDir = $dir;
    chdir($dir);
}

/**
 * On inclue une seule fois les fichier d'autoload ici, ce qui nous permet de ne pas le refaire dans 
 * les autres bootstrap, et de juste ajouter le namespace correspondant.
 */
if  (!(($loader = @include_once __DIR__.'../../../../vendor/autoload.php') || !($loader = @include_once __DIR__.'../../../../autoload.php'))) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('ApplicationTest\\', __DIR__);

if (!$config = @include __DIR__ . '/TestConfig.php') {
    $config = require __DIR__ . '/TestConfig.php.dist';
}

\ApplicationTest\Util\ServiceManagerFactory::setConfig($config);