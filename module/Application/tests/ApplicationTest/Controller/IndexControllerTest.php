<?php
/**
 * 
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>  
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use ApplicationTest\Framework\TestCase;

class IndexControllerTest extends TestCase {


    public function setUp()
    {
        /**
         * set up obligatoire pour les setUp de controlleur. On étend aussi la classe TestCase
         * Qui se charge à la différence des autres TestCase d'autre module d'etendre cette fois
         * ci AbstractHttpControllerTestCase de Zend
         */
        $this->setApplicationConfig(
            include __DIR__ . '/../../TestConfig.php.dist'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('home');
        $this->assertControllerClass('IndexController');
        $this->assertModuleName('Application');
    }

}