<?php
/**
 * @extends PHPUnit_Framework_TestCase
 * Permet de récupérer l'entity Manager en utilisant la factory et de faire d'autres actions
 * Accessible dans nos classe de test ! On factorise l'accès à l'EntityManager
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */


namespace AlbumTest\Framework;

use PHPUnit_Framework_TestCase;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use AlbumTest\Util\ServiceManagerFactory;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Get EntityManager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager) {
            return $this->entityManager;
        }

        $serviceManager = ServiceManagerFactory::getServiceManager();
        $serviceManager->get('doctrine.entity_resolver.orm_default');
        $this->entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        return $this->entityManager;
    }
}