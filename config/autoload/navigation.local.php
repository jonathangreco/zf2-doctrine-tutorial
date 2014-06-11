<?php
/**
 * @package config
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>  
 */ 
return array(
    'navigation' => array(
        'default' => array(
            'Album' => array(
                'label' => 'Album',
                'route' => 'album',
                'pages' => array(
                 array(
                     'label' => 'Add',
                     'route' => 'album/default',
                     'action' => 'add',
                 ),
                 array(
                     'label' => 'Edit',
                     'route' => 'album/default',
                     'action' => 'edit',
                 ),
                 array(
                     'label' => 'Delete',
                     'route' => 'album/default',
                     'action' => 'delete',
                 ),
                 array(
                     'label' => 'Page',
                     'route' => 'album/page',
                 ),
                ),
            ),
		),
	),
);