<?php
/**
 * @package Album
 * @author Florent Blaison <florent.blaison@gmail.com>
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
