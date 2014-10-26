<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Exception\GlobalPostNotSet;
use WordPressHMVC\Post\Exception\PostNotExist;
use WordPressHMVC\Post\Factory\PostFactory;
use WordPressHMVC\Post\Model\Post;

class PostManager {
	/** @var PostFactory */
	private $_postFactory;

	public function __construct( PostFactory $postFactory ) {
		$this->_postFactory = $postFactory;
	}

	/**
	 * Retrieve adjacent post.
	 *
	 * Can either be next or previous post.
	 *
	 * @param bool   $inSameTerm    Optional. Whether post should be in a same taxonomy term.
	 * @param array  $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param bool   $previous      Optional. Whether to retrieve previous post.
	 * @param string $taxonomyName  Optional. Taxonomy Name, if $inSameTerm is true. Default 'category'.
	 *
	 * @return \WordPressHMVC\Post\Model\Post
	 */
	public function getAdjacentPost( $inSameTerm = false, $excludedTerms = array(), $previous = true, $taxonomyName = 'category' ) {
		$result = get_adjacent_post( $inSameTerm, $excludedTerms, $previous, $taxonomyName );
		if ( is_null( $result ) ) {
			throw new GlobalPostNotSet();
		} elseif ( empty( $result ) ) {
			throw new PostNotExist();
		}
		$post = $this->_postFactory->create( $result );

		return $post;
	}

	/**
	 * Retrieve boundary post.
	 *
	 * Boundary being either the first or last post by publish date within the constraints specified
	 * by $inSameTerm or $excludedTerms.
	 *
	 * @param bool   $inSameTerm    Optional. Whether returned post should be in a same taxonomy term.
	 * @param array  $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param bool   $start         Optional. Whether to retrieve first or last post.
	 * @param string $taxonomy      Optional. Taxonomy, if $in_same_term is true. Default 'category'.
	 *
	 * @return \WordPressHMVC\Post\Model\Post
	 */
	public function getBoundaryPost( $inSameTerm = false, $excludedTerms = array(), $start = true, $taxonomy = 'category' ) {
		$result = get_boundary_post( $inSameTerm, $excludedTerms, $start, $taxonomy );
		if ( is_null( $result ) ) {
			throw new GlobalPostNotSet();
		} elseif ( empty( $result ) ) {
			throw new PostNotExist();
		}
		$post = $this->_postFactory->create( $result );

		return $post;
	}

	/**
	 * Retrieves the post currently in context
	 *
	 * @return Post
	 */
	public function getCurrentPost() {
		return $this->_getPost();
	}

	/**
	 * Retrieves post.
	 *
	 * @param int $postId Post ID
	 *
	 * @return Post
	 */
	public function getPost( $postId ) {
		return $this->_getPost( $postId );
	}

	/**
	 * Retrieve list of latest posts or posts matching criteria.
	 *
	 * @param array $queryArgs Optional. Arguments to retrieve posts. {@see WP_Query::parse_query()} for more available
	 *                         arguments.
	 *
	 * @return array
	 */
	public function getPosts( array $queryArgs = array() ) {
		$results = get_posts( $queryArgs );
		$posts   = array();
		if ( is_array( $results ) ) {
			foreach ( $results as $post ) {
				$posts[] = $this->_postFactory->create( $post );
			}
		}

		return $posts;
	}

	/**
	 * Retrieves post.
	 *
	 * @param int|null $postId Optional. Post ID. Defaults to global $post.
	 *
	 * @return Post
	 */
	private function _getPost( $postId = null ) {
		$result = get_post( $postId );
		if ( is_null( $result ) ) {
			throw new PostNotExist();
		}
		$post = $this->_postFactory->create( $result );

		return $post;
	}
} 