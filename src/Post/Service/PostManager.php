<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Exception\GlobalPostNotSet;
use WordPressHMVC\Post\Exception\PostNotExist;
use WordPressHMVC\Post\Factory\PostFactory;

class PostManager {
	/** @var PostFactory */
	private $_postFactory;

	public function __construct( PostFactory $postFactory ) {
		$this->_postFactory = $postFactory;
	}

	public function getAdjacentPost( $inSameTerm = false, $excludedTerms = array(), $previous = true, $taxonomyName = 'category' ) {
		$result = get_adjacent_post( $inSameTerm, $excludedTerms, $previous, $taxonomyName );
		if ( is_null( $result ) ) {
			throw new GlobalPostNotSet();
		}
		if ( empty( $result ) ) {
			throw new PostNotExist();
		}
		$post = $this->_postFactory->create( $result );

		return $post;
	}
} 