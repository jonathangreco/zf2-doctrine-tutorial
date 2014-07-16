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

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $routeCallback = function ($e) {
            $availableLanguages = array ('fr', 'en');
            $defaultLanguage = 'en';
            $language = "";
            $fromRoute = false;
            //see if language could be find in url
            if ($e->getRouteMatch()->getParam('lang')) {
                $language = $e->getRouteMatch()->getParam('lang');
                $fromRoute = true;

                //or use language from http accept
            } else {
                $headers = $e->getApplication()->getRequest()->getHeaders();
                if ($headers->has('Accept-Language')) {
                    $headerLocale = $headers->get('Accept-Language')->getPrioritized();
                    $language = substr($headerLocale[0]->getLanguage(), 0,2);
                }
            }
            if(!in_array($language, $availableLanguages) ) {
                $language = $defaultLanguage;
            }
            $e->getApplication()->getServiceManager()->get('translator')->setLocale($language);

        };

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, $routeCallback);
        // Récupère tous les messages FlashMessages
        $eventManager->attach(MvcEvent::EVENT_RENDER, function($e) {
            $flashMessenger = new FlashMessenger;
     
            $messages = array();
     
            $flashMessenger->setNamespace('success');
            if ($flashMessenger->hasMessages()) {
                $messages['success'] = $flashMessenger->getMessages();
            }
            $flashMessenger->clearMessages();
            
            $flashMessenger->setNamespace('warning');
            if ($flashMessenger->hasMessages()) {
                $messages['warning'] = $flashMessenger->getMessages();
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
}

