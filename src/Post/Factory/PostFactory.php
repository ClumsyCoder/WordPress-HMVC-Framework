<?php

namespace WordPressHMVC\Post\Factory;

use WordPressHMVC\Post\Collection\PostList;
use WordPressHMVC\Post\Model\Post;
use WordPressHMVC\SiteManager;

class PostFactory {
	/** @var SiteManager */
	private $_siteManager;

	public function __construct( SiteManager $siteManager ) {
		$this->_siteManager = $siteManager;
	}

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
	public function createList( $postsData ) {
		$posts = array();
		if ( ! empty( $postsData ) ) {
			foreach ( $postsData as $postData ) {
				$posts[] = $this->create( $postData );
			}
		}

		return new PostList( new \ArrayObject( $posts ) );
	}

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

		$post->setPublicationDate( new \DateTime( $wpPost->post_date, $this->_siteManager->getTimezone() ) );
		$post->setPublicationGmtDate( new \DateTime( $wpPost->post_date_gmt, $this->_siteManager->getGmtTimezone() ) );

		$post->setModifiedDate( new \DateTime( $wpPost->post_modified, $this->_siteManager->getTimezone() ) );
		$post->setModifiedGmtDate( new \DateTime( $wpPost->post_modified_gmt, $this->_siteManager->getGmtTimezone() ) );

		return $post;
	}
}