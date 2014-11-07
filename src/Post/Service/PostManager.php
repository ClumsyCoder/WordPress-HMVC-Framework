<?php

namespace WordPressSolid\Post\Service;

use WordPressSolid\Post\Exception\GlobalPostNotSet;
use WordPressSolid\Post\Exception\PostNotExist;
use WordPressSolid\Post\Factory\PostFactory;
use WordPressSolid\Post\Model\Post;
use WordPressSolid\Post\Collection\PostList;

/**
 * Class PostManager
 * @package WordPressHMVC\Post\Service
 */
class PostManager {
	/** @var PostFactory */
	private $_postFactory;

	/**
	 * @param PostFactory $postFactory
	 */
	public function __construct( PostFactory $postFactory ) {
		$this->_postFactory = $postFactory;
	}

	/**
	 * Get previous post in context by publish date
	 *
	 * @param bool         $inSameTerm    Optional. Whether post should be in a same taxonomy term.
	 * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param string       $taxonomyName  Optional. Taxonomy Name, if $inSameTerm is true. Default 'category'.
	 *
	 * @return \WordPressSolid\Post\Model\Post
	 */
	public function getPreviousPost( $inSameTerm = false, $excludedTerms = array(), $taxonomyName = 'category' ) {
		return $this->_getAdjacentPost( $inSameTerm, $excludedTerms, true, $taxonomyName );
	}

	/**
	 * Get next post in context by publish date
	 *
	 * @param bool         $inSameTerm    Optional. Whether post should be in a same taxonomy term.
	 * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param string       $taxonomyName  Optional. Taxonomy Name, if $inSameTerm is true. Default 'category'.
	 *
	 * @return Post
	 */
	public function getNextPost( $inSameTerm = false, $excludedTerms = array(), $taxonomyName = 'category' ) {
		return $this->_getAdjacentPost( $inSameTerm, $excludedTerms, false, $taxonomyName );
	}

	/**
	 * Get first post in context
	 *
	 * @param bool         $inSameTerm    Optional. Whether returned post should be in a same taxonomy term.
	 * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param string       $taxonomyName  Optional. Taxonomy, if $in_same_term is true. Default 'category'.
	 *
	 * @return Post
	 */
	public function getFirstPost( $inSameTerm = false, $excludedTerms = array(), $taxonomyName = 'category' ) {
		return $this->_getBoundaryPost( $inSameTerm, $excludedTerms, true, $taxonomyName );
	}

	/**
	 * Get last post in context
	 *
	 * @param bool         $inSameTerm    Optional. Whether returned post should be in a same taxonomy term.
	 * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param string       $taxonomyName  Optional. Taxonomy, if $in_same_term is true. Default 'category'.
	 *
	 * @return Post
	 */
	public function getLastPost( $inSameTerm = false, $excludedTerms = array(), $taxonomyName = 'category' ) {
		return $this->_getBoundaryPost( $inSameTerm, $excludedTerms, false, $taxonomyName );
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
	 * @return PostList
	 */
	public function getPosts( array $queryArgs = array() ) {
		return $this->_postFactory->createList( get_posts( $queryArgs ) );
	}

	/**
	 * Retrieve a number of recent posts.
	 *
	 * @param array $queryArgs
	 *
	 * @return PostList
	 */
	public function getRecentPosts( array $queryArgs = array() ) {
		$result = wp_get_recent_posts( $queryArgs );
		if ( $result == false ) {
			$result = array();
		}

		return $this->_postFactory->createList( $result );
	}

	/**
	 * Retrieve ancestors of a post.
	 *
	 * @param Post $post
	 *
	 * @return PostList
	 */
	public function getPostAncestors( Post $post ) {
		return $this->_postFactory->createList( get_post_ancestors( $post->getId() ) );
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

		return $this->_postFactory->create( $result );
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
	 * @return \WordPressSolid\Post\Model\Post
	 */
	private function _getBoundaryPost( $inSameTerm = false, $excludedTerms = array(), $start = true, $taxonomy = 'category' ) {
		global $post;
		if ( empty( $post ) ) {
			throw new GlobalPostNotSet();
		}
		$result = get_boundary_post( $inSameTerm, $excludedTerms, $start, $taxonomy );
		if ( empty( $result ) ) {
			throw new PostNotExist();
		}

		return $this->_postFactory->create( $result );
	}

	/**
	 * Get an adjacent post for the current context
	 *
	 * @param bool         $inSameTerm    Optional. Whether post should be in a same taxonomy term.
	 * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs.
	 * @param bool         $previous      Optional. Whether to retrieve previous post.
	 * @param string       $taxonomyName  Optional. Taxonomy, if $in_same_term is true. Default 'category'.
	 *
	 * @return Post
	 */
	private function _getAdjacentPost( $inSameTerm = false, $excludedTerms = array(), $previous = true, $taxonomyName = 'category' ) {
		$result = get_adjacent_post( $inSameTerm, $excludedTerms, $previous, $taxonomyName );
		if ( is_null( $result ) ) {
			throw new GlobalPostNotSet();
		} elseif ( empty( $result ) ) {
			throw new PostNotExist();
		}

		return $this->_postFactory->create( $result );
	}
}