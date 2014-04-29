<?php

/**
 * @package Album
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Album\Factory;

use Album\Controller\AlbumController;
use Album\Service\AlbumService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AlbumControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AlbumService $albumService */
        $albumService = $parentLocator->get('Album\Service\Album');
        return new AlbumController($albumService);
    }
}
