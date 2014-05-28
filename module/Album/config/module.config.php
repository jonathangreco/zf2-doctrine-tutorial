<?php
/**
 * @package Album
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */
namespace Album;

return array(
    'controllers'     => array(
        'factories' => array(
            'Album\Controller\Album' => 'Album\Factory\AlbumControllerFactory',
        ),
    ),
    'router'          => array(
        'routes' => array(
            'album' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'       => '/album',
                    'defaults'    => array(
                        '__NAMESPACE__' => 'Album\Controller',
                        'controller'    => 'Album',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'=> 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                        ),
                    ),
                    'page' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'       => '/[page/:page]',
                            'constraints' => array(
                                'page'     => '[0-9]*',
                            ),
                            'defaults'    => array(
                                'controller' => 'Album\Controller\Album',
                                'action'     => 'index',
                                'page'      =>1
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
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
    /**
     * Permet de déclarer toutes les routes manuellement
     * Plus performant que template_path_stack
     */ 
    'view_manager'    => array(
        'template_map' => array(
            'album/album/index'  => __DIR__ . '/../view/album/album/index.phtml',
            'album/album/add'    => __DIR__ . '/../view/album/album/add.phtml',
            'album/album/edit'   => __DIR__ . '/../view/album/album/edit.phtml',
            'album/album/delete' => __DIR__ . '/../view/album/album/delete.phtml',
            'album/album/page_helper' => __DIR__ . '/../view/album/album/page_album.phtml',
        ),
    ),
    'form_elements'   => array(
        'invokables' => array(
            'Album\Form\AddAlbum' => 'Album\Form\AddAlbumForm',
            'Album\Form\EditAlbum' => 'Album\Form\EditAlbumForm',
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