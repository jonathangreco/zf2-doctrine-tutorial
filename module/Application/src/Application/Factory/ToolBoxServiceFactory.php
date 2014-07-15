<?php
/**
 * Factory permettant d'inhecter une instance de l'object manager doctrine
 * via le service locator dans le service ToolBox et de retourner une instance 
 * de ToolBoxService
 * @package ToolBox
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */

namespace Application\Factory;

use Application\Service\ToolBoxService;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ToolBoxServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return new ToolBoxService($objectManager);
    }
}
