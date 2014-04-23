<?php

/**
 * Classe permettant les traitements sur la table album
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */

namespace Album\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 

use Album\Entity\Album;

class AlbumService implements ServiceManagerAwareInterface
{

	protected $sm;
	protected $em;
    protected $inputFilter;

    public function setServiceManager(ServiceManager $serviceManager)
    {
    	$this->sm = $serviceManager;
    	return $this;
    }

    /*
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->sm;
    }

    public function fetchAll()
    {
    	$this->em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
    	return $this->em->getRepository('Album\Entity\Album')->findAll();
    }

    public function getAlbum($id)
    {
        $this->em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        return $this->em->getRepository('Album\Entity\Album')->find($id);

    }

    public function saveAlbum(Album $album)
    {
    	$this->em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
    	$connect = $this->em->getConnection();

        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
        );

        $id = (int)$album->id;
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

    public function deleteAlbum($id)
    {
    	$this->em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
    	$connect = $this->em->getConnection();
        $connect->delete('Album',array('id' => $id));
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->artist = (isset($data['artist'])) ? $data['artist'] : null;
        $this->title  = (isset($data['title'])) ? $data['title'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'artist',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;        
        }

        return $this->inputFilter;
    }
}