<?php
/**
 * L'album fieldset permet de définir un groupe de champs dépendants entre eux
 * et les liants a leur entité doctrine (dans le cas d'un contexte 
 * de formulaire par table) cette classe lorsqu'elle étend InputFilterProviderInterface
 * permet de définir les validateurs pour le bon fonctionnement du formulaire
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */
namespace Album\Form\Fieldset;

use Album\Entity\Album;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager as ProvidesObjectManagerTrait ;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AlbumFieldset extends Fieldset implements
    ObjectManagerAwareInterface,
    InputFilterProviderInterface
{
    //un trait depuis php5.4 est en réalité une implémentation de méthodes génériques lorsque l'on implémente une
    //classe. Ici ProvidesObjectManagerTrait implémente 2 méthodes abstraites, mais qui sont très générique partout
    //puisqu'elle permettent de récupérer l'objectManager, le trait fait cela pour nous.
    //Et le code reste qu'a un seul endroit.
    use ProvidesObjectManagerTrait;

    public function init()
    {
        $this->setHydrator(new DoctrineHydrator($this->getObjectManager(), 'Album\Entity\Album'))
            ->setObject(new Album());

        $this->add(
            array(
                'name' => 'id',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            )
        );
        $this->add(
            array(
                'name'    => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => 'Title',
                ),
            )
        );
        $this->add(
            array(
                'name'    => 'artist',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => 'Artist',
                ),
            )
        );
    }

    /**
     * Set requirements for validation 
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
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