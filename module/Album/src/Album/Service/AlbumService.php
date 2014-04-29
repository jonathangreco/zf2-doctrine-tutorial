<?php

/**
 * Classe permettant les traitements sur la table album
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Album\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Album\Entity\Album;

class AlbumService implements ServiceLocatorAwareInterface
{

    //un trait depuis php5.4 est en réalité une implémentation de méthodes génériques lorsque l'on implémente une
    //classe. Ici ServiceLocatorAwareInterface implémente 2 méthodes abstraites, mais qui sont très générique partout
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
     * @param  null
     * @return  collection of Album
     */ 
    public function getAll()
    {
    	return $this->em->getRepository('Album\Entity\Album')->findAll();
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
     * @return  Exception if id is null
     * update Album
     */
    public function updateAlbum(Album $album)
    {
    	$connect = $this->em->getConnection();

        $data = array(
            'artist' => $album->getArtist(),
            'title'  => $album->getTitle(),
        );
        $id = (int)$album->getId();
        if ($id == 0) {
            $connect->insert('Album', $data);
        } else {
            if ($this->getAlbum($id)) {
                $connect->update('Album', $data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    /**
     * @param  $id Id of album to delete
     */ 
    public function deleteAlbum($id)
    {
    	$connect = $this->em->getConnection();
        $connect->delete('Album',array('id' => $id));
    }
}