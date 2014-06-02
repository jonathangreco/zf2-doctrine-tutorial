<?php

/**
 * Classe permettant les traitements sur la table album
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 */

namespace Album\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

use Album\Entity\Album;

class AlbumService implements ServiceLocatorAwareInterface
{

    //un trait depuis php5.4 est en réalité une implémentation de méthodes génériques lorsque l'on implémente une
    //classe. Ici ServiceLocatorAwareInterface implémente 2 méthodes, mais qui sont très générique partout
    //puisqu'elle permettent de récupérer le serviceManager, le trait fait cela pour nous.
    //Et le code reste qu'a un seul endroit.
    use ServiceLocatorAwareTrait;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @var InputFilter
     */
    private $inputFilter;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * Fonction permettant de retourner un adapter doctrine pour la table Album
     * Utilisé pour la pagination
     * @return $adapter DoctrineAdapter instance
     */
    public function getAdapter()
    {
        $repository = $this->em->getRepository('Album\Entity\Album');
        $adapter = new DoctrineAdapter(new ORMPaginator($repository->createQueryBuilder('album')));

        return $adapter;
    }

    /**
     * @param   $id | int 
     * @return  Instance of ALbum
     */ 
    public function getAlbum($id)
    {
        return $this->em->getRepository('Album\Entity\Album')->find($id);

    }

    /**
     * @param   $album Instance of Album
     * Save album into Database
     */ 
    public function addAlbum(Album $album)
    {
        $this->em->persist($album);
        $this->em->flush($album);
    }

    /**
     * @param   $album Instance of Album
     * update Album
     */
    public function updateAlbum(Album $album)
    {
        $this->em->flush($album);
    }

    /**
     * @param  $album Instance of Album
     */ 
    public function deleteAlbum(Album $album)
    {
        $this->em->remove($album);
        $this->em->flush($album);
    }
}