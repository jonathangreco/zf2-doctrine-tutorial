<?php
/**
 *
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */
namespace Album;

return array(
    'controllers'     => array(
        'factories' => array(
            'Album\Controller\Album' => 'Album\Factory\AlbumControllerFactory',
        ),
    ),
    // The following section is new and should be added to your file
    'router'          => array(
        'routes' => array(
            'album' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/album[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Album\Controller\Album',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Album\Service\Album' => 'Album\Factory\AlbumServiceFactory',
        ),
    ),
    'view_manager'    => array(
        'template_map' => array(
            'album/album/index'  => __DIR__ . '/../view/album/album/index.phtml',
            'album/album/add'    => __DIR__ . '/../view/album/album/add.phtml',
            'album/album/edit'   => __DIR__ . '/../view/album/album/edit.phtml',
            'album/album/delete' => __DIR__ . '/../view/album/album/delete.phtml',
        ),
    ),
    'form_elements'   => array(
        'invokables' => array(
            'Album\Form\AddAlbum' => 'Album\Form\AlbumForm',
        ),
    ),
    'doctrine'        => array(
        'driver' => array(
            'album_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),
            'orm_default'  => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => 'album_entity',
                )
            )
        )
    ),
);