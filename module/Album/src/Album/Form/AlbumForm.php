<?php
/**
 *
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */
namespace Album\Form;

use Zend\Form\Form;

class AlbumForm extends Form
{
    /**
     * Permet la configuration du formulaire d'ajout ou de modification des Album
     * Un album fieldset est en réalité toutes les specification et le regroupement
     * de champs d'un formulaire. Et ainsi permettre a des champs de formulaire de 
     * constituer des groupes de validation.
     * ici title et artist sont regroupé dans un fieldset avec l'id
     */ 
    public function init()
    {
        $this->add(
            array(
                'name'    => 'album',
                'type'    => 'Album\Form\Fieldset\AlbumFieldset',
                'options' => array(
                    'use_as_base_fieldset' => true,
                ),
            )
        );

        // csrf element
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'add_album_csrf',
            )
        );

        $this->setValidationGroup(
            array(
                'album' => array(
                    'title',
                    'artist',
                ),
                'add_album_csrf',
            )
        );

        $this->add(
            array(
                'name'       => 'submit',                
                'attributes' => array(
                    'type'       => 'Submit',
                    'value' => 'Add',
                    'id'    => 'submitbutton',
                ),
            )
        );
    }
}