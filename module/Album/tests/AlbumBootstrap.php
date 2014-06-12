<?php
/**
 * Ceci est le boostrap d'ocramius que nous testons au sein de notre Album
 * Poir vérifier si l'architecture de notre projet est faisable plus facilement;
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>  
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
/* Au lancement du bootstrap application pour les tests unitaires, une instance de loader est deja créée
 * pas la peine d'en créer une seconde, On ajoute juste le namespace Album
 */

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('AlbumTest\\', __DIR__);

if (!$config = @include __DIR__ . '/TestConfig.php') {
    $config = require __DIR__ . '/TestConfig.php.dist';
}

\AlbumTest\Util\ServiceManagerFactory::setConfig($config);