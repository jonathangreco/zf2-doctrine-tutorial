<?php
/**
 *
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>  
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Session\Container;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;
use Zend\Http\Response;
use Application\View\Helper\AbsoluteUrl;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // container de session
        $sessionContainer = new Container('locale');

        // teste si la langue en session existe
        if(!$sessionContainer->offsetExists('mylocale')){
            $headers = $e->getApplication()->getRequest()->getHeaders();
            if ($headers->has('Accept-Language')) {
                $headerLocale = $headers->get('Accept-Language')->getPrioritized();
                $language = substr($headerLocale[0]->getLanguage(), 0,2);
            }
        }

        // mise en place du service de traduction
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator ->setLocale($sessionContainer->mylocale)
        ->setFallbackLocale('en_US');

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $FlashMessenger = $e->getApplication()->getServiceManager()->get('FlashMessageListener');
        $FlashMessenger->attach($eventManager);
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

