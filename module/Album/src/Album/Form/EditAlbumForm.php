<?php
/**
 * @package Album
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */
namespace Album\Form;

use Zend\Form\Form;

class EditAlbumForm extends Form
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
                'name' => 'edit_album_csrf',
            )
        );

        $this->setValidationGroup(
            array(
                'album' => array(
                    'title',
                    'artist',
                ),
                'edit_album_csrf',
            )
        );

        $this->add(
            array(
                'name'       => 'submit',                
                'attributes' => array(
                    'type'       => 'Submit',
                    'value' => 'Edit',
                    'id'    => 'submitbutton',
                ),
            )
        );
    }
}