<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * 
 * Cette classe regroupe des fonctions communes de l'application
 * 
 */

namespace Application\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ToolBoxService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use DataObjectAccessTrait;
    use DirectoryAndFileOperationTrait;
    use LogTrait;
    use MailTrait;
    use FormatStringTrait;
    
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * Constructeur avec injection de dépendant de l'ObjectManager
     * 
     * @param  $em ObjectManager 
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
}