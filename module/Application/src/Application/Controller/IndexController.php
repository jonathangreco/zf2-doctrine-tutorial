<?php
/**
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
