<?php

namespace WordPressSolid\Post\Factory;

use WordPressSolid\Post\Collection\PostList;
use WordPressSolid\Post\Model\Post;

class PostFactoryTest extends \WP_UnitTestCase {
	/** @var \WordPressSolid\SiteManager */
	private $_siteManagerMock;
	/** @var PostFactory */
	private $_postFactory;

	public function setUp() {
		parent::setUp();
		$this->_siteManagerMock = $this->getMockBuilder( '\WordPressHMVC\SiteManager' )
		                               ->getMock();
		$this->_siteManagerMock->method( 'getTimezone' )
		                       ->willReturn( new \DateTimeZone( 'America/Toronto' ) );
		$this->_siteManagerMock->method( 'getGmtTimezone' )
		                       ->willReturn( new \DateTimeZone( 'GMT' ) );
		$this->_postFactory = new PostFactory( $this->_siteManagerMock );
	}

	public function testCreate_When_Given_WP_Post() {
		$wpPost       = $this->factory->post->create_and_get();
		$expectedPost = $this->_postFromWpPost( $wpPost );
		$post         = $this->_postFactory->create( $wpPost );
		$this->assertEquals( $expectedPost, $post );
	}

	public function testCreateList_When_Given_List_Of_WP_Post() {
		$wpPostList = array();
		for ( $i = 0; $i < 10; $i ++ ) {
			$wpPostList[] = $this->factory->post->create_and_get();
		}

		$expectedPosts = array();
		foreach ( $wpPostList as $wpPost ) {
			$expectedPosts[] = $this->_postFromWpPost( $wpPost );
		}
		$expectedPostList = new PostList( new \ArrayObject( $expectedPosts ) );

		$postList = $this->_postFactory->createList( $wpPostList );
		$this->assertEquals( $expectedPostList, $postList );
	}

	public function testCreateList_When_Given_List_Is_Empty() {
		$postList = $this->_postFactory->createList( array() );
		$this->assertEmpty( $postList );
	}

	/**
	 * Create a Post object from a WP_Post object
	 *
	 * @param \WP_Post $wpPost
	 *
	 * @return Post
	 */
	private function _postFromWpPost( \WP_Post $wpPost ) {
		$expectedPost = new Post();
		$expectedPost->setId( $wpPost->ID );
		$expectedPost->setAuthorId( $wpPost->post_author );
		$expectedPost->setName( $wpPost->post_name );
		$expectedPost->setTitle( $wpPost->post_title );
		$expectedPost->setContent( $wpPost->post_content );
		$expectedPost->setExcerpt( $wpPost->post_excerpt );
		$expectedPost->setStatus( $wpPost->post_status );
		$expectedPost->setPassword( $wpPost->post_password );
		$expectedPost->setCommentCount( $wpPost->comment_count );
		$expectedPost->setCommentStatus( $wpPost->comment_status );
		$expectedPost->setPublicationDate( new \DateTime( $wpPost->post_date, $this->_siteManagerMock->getTimezone() ) );
		$expectedPost->setPublicationGmtDate( new \DateTime( $wpPost->post_date_gmt, $this->_siteManagerMock->getGmtTimezone() ) );
		$expectedPost->setModifiedDate( new \DateTime( $wpPost->post_modified, $this->_siteManagerMock->getTimezone() ) );
		$expectedPost->setModifiedGmtDate( new \DateTime( $wpPost->post_modified_gmt, $this->_siteManagerMock->getGmtTimezone() ) );

		return $expectedPost;
	}
}
