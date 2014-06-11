<?php
/**
 * @extends AbstractHttpControllerTestCase
 * Permet de récupérer l'entity Manager en utilisant la factory et de faire d'autres actions
 * Accessible dans nos classe de test ! On factorise l'accès à l'EntityManager
 * 
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>  
 */

namespace ApplicationTest\Framework;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use ApplicationTest\Util\ServiceManagerFactory;

class TestCase extends AbstractHttpControllerTestCase
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