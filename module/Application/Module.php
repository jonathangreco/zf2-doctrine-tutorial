<?php
/**
 *
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Application\View\Helper\AbsoluteUrl;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $sessionContainer = new Container('locale');
        // teste si la langue en session existe
        if(!$sessionContainer->offsetExists('mylocale')){
            // n'existe pas donc on ajoute la langue par défaut
            $sessionContainer->offsetSet('mylocale', 'fr_FR');
        }
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator ->setLocale($sessionContainer->mylocale)
        ->setFallbackLocale('en_US');

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                // the array key here is the name you will call the view helper by in your view scripts
                'absoluteUrl' => function($sm) {
                    $locator = $sm->getServiceLocator(); // $sm is the view helper manager, so we need to fetch the main service manager
                    return new AbsoluteUrl($locator->get('Request'));
                },
            ),
        );
    }

}
