<?php

namespace WordPressHMVC\Post\Exception;

class GlobalPostNotSet extends \RuntimeException {
	protected $message = 'The global $post is not set';
} 