<?php
/**
 *
 * @package Album
 * @author Florent Blaison <florent.blaison@gmail.com>
 */
namespace Album\Form;

use Zend\Form\Form;

class AddAlbumForm extends Form
{
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
                'type'       => 'Submit',
                'attributes' => array(
                    'value' => 'Add',
                    'id'    => 'submitbutton',
                ),
            )
        );
    }
}