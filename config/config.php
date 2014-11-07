<?php

/**
 * Default configuration file for the Solid Framework
 */
return array(
	'services' => array(
		'postManager' => array(
			'class'       => 'WordPressSolid\Post\Service\PostManager',
			'constructor' => array(
				new WordPressSolid\Post\Factory\PostFactory(),
			),
		),
	),
);