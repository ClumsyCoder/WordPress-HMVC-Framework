<?php

/**
 * Default configuration file for the Solid Framework
 */
return array(
	'services' => array(
		'postManager' => array(
			'class'  => '\WordPressSolid\Post\Service\PostManager',
			'params' => array(
				array(
					'type'   => 'object',
					'class'  => '\WordPressSolid\Post\Factory\PostFactory',
					'params' => array(),
				),
			),
		),
	),
);