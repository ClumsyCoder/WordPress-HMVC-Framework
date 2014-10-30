<?php

namespace WordPressHMVC\Post\Exception;

/**
 * Class PostNotExist
 * @package WordPressHMVC\Post\Exception
 */
class PostNotExist extends \RuntimeException {
	protected $message = 'No corresponding post exists';
} 