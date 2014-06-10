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
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Session\Container;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

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

        // Show flashmessages in the view
        $eventManager->attach(MvcEvent::EVENT_RENDER, function($e) {
            $flashMessenger = new FlashMessenger;
     
            $messages = array();
     
            $flashMessenger->setNamespace('success');
            if ($flashMessenger->hasMessages()) {
                $messages['success'] = $flashMessenger->getMessages();
            }
            $flashMessenger->clearMessages();
     
            $flashMessenger->setNamespace('info');
            if ($flashMessenger->hasMessages()) {
                $messages['info'] = $flashMessenger->getMessages();
            }
            $flashMessenger->clearMessages();
     
            $flashMessenger->setNamespace('default');
            if ($flashMessenger->hasMessages()) {
                if (isset($messages['info'])) {
                    $messages['info'] = array_merge($messages['info'], $flashMessenger->getMessages());
                }
                else {
                    $messages['info'] = $flashMessenger->getMessages();
                }
            }
            $flashMessenger->clearMessages();
     
            $flashMessenger->setNamespace('error');
            if ($flashMessenger->hasMessages()) {
                $messages['error'] = $flashMessenger->getMessages();
            }
            $flashMessenger->clearMessages();
     
            $e->getViewModel()->setVariable('flashMessages', $messages);
        });
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

