<?php
/**
 * 
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Album;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\EventManager\EventInterface;
use Zend\Form\FormElementManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    FormElementProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        /* @var $application \Zend\Mvc\Application */
        $application         = $e->getTarget();
        $eventManager        = $application->getEventManager();
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

    public function getFormElementConfig()
    {
        return array(
            'initializers' => array(
                'ObjectManagerInitializer' => function ($element, FormElementManager    $formElements) {
                    if ($element instanceof ObjectManagerAwareInterface) {
                        /** @var ServiceLocatorInterface $serviceLocator */
                        $serviceLocator = $formElements->getServiceLocator();
                        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

                        $element->setObjectManager($entityManager);
                    }
                },
            ),
        );
    }
}
