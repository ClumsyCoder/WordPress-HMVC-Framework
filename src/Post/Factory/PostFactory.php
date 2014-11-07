<?php

namespace WordPressSolid\Post\Factory;

use WordPressSolid\Date;
use WordPressSolid\FactoryInterface;
use WordPressSolid\Post\Collection\PostList;
use WordPressSolid\Post\Model\Post;

/**
 * Class PostFactory
 * @package WordPressHMVC\Post\Factory
 */
class PostFactory implements FactoryInterface {
	/**
	 * Create a post object from post data.  This post data can be a WP_Post object.
	 *
	 * @param mixed|\WP_Post $postData
	 *
	 * @return Post
	 */
	public function create( $postData ) {
		if ( $postData instanceof \WP_Post ) {
			$post = $this->_createPostFromWpPost( $postData );
		} else {
			$post = new Post();
		}

		return $post;
	}

	/**
	 * Create a post list from an array of post data
	 *
	 * @param array $postsData
	 *
	 * @return PostList
	 */
	public function createList( array $postsData ) {
		$posts = array();
		if ( ! empty( $postsData ) ) {
			foreach ( $postsData as $postData ) {
				$posts[] = $this->create( $postData );
			}
		}

		return new PostList( new \ArrayObject( $posts ) );
	}

	/**
	 * Create a post object from a WP_Post object
	 *
	 * @param \WP_Post $wpPost
	 *
	 * @return Post
	 */
	private function _createPostFromWpPost( \WP_Post $wpPost ) {
		$post = new Post();

		$post->setId( $wpPost->ID );
		$post->setAuthorId( $wpPost->post_author );
		$post->setName( $wpPost->post_name );
		$post->setTitle( $wpPost->post_title );
		$post->setContent( $wpPost->post_content );
		$post->setExcerpt( $wpPost->post_excerpt );
		$post->setStatus( $wpPost->post_status );
		$post->setPassword( $wpPost->post_password );

		$post->setCommentCount( $wpPost->comment_count );
		$post->setCommentStatus( $wpPost->comment_status );

		$post->setPublicationDate( Date::getDate( $wpPost->post_date ) );
		$post->setPublicationGmtDate( Date::getGmtDate( $wpPost->post_date_gmt ) );

		$post->setModifiedDate( Date::getDate( $wpPost->post_modified ) );
		$post->setModifiedGmtDate( Date::getGmtDate( $wpPost->post_modified_gmt ) );

		return $post;
	}
}