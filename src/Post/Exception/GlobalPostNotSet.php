<?php

namespace WordPressHMVC\Post\Exception;

/**
 * Class GlobalPostNotSet
 * @package WordPressHMVC\Post\Exception
 */
class GlobalPostNotSet extends \RuntimeException {
	protected $message = 'The global $post is not set';
} 