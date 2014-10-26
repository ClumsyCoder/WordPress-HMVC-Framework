<?php

namespace WordPressHMVC\Post\Exception;

class PostNotExist extends \RuntimeException {
	protected $message = 'No corresponding post exists';
} 