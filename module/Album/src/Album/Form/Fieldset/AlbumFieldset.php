<?php

/**
 * @package Album
 * @author Florent Blaison <florent.blaison@gmail.com>
 */
namespace Album\Form\Fieldset;

use Album\Entity\Album;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AlbumFieldset extends Fieldset implements
    ObjectManagerAwareInterface,
    InputFilterProviderInterface
{
    use ProvidesObjectManager;

    public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->getObjectManager(), 'Album\Entity\Album'))
            ->setObject(new Album());

        $this->add(
            array(
                'name' => 'id',
                'type' => 'Hidden',
            )
        );
        $this->add(
            array(
                'name'    => 'title',
                'type'    => 'Text',
                'options' => array(
                    'label' => 'Title',
                ),
            )
        );
        $this->add(
            array(
                'name'    => 'artist',
                'type'    => 'Text',
                'options' => array(
                    'label' => 'Artist',
                ),
            )
        );
    }

    /**
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'id'     => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ),
            'artist' => array(
                'required'   => true,
                'filters'    => array(
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
            ),
            'title'  => array(
                'required'   => true,
                'filters'    => array(
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
            )
        );
    }
} 