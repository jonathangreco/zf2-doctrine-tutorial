<?php
/**
 * Factory permettant d'inhecter une instance de l'object manager doctrine
 * via le service locator dans le service Album et de retourner une instance 
 * de AlbumService
 * @package Album
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */

namespace Album\Factory;

use Album\Service\AlbumService;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AlbumServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return new AlbumService($objectManager);
    }
}
