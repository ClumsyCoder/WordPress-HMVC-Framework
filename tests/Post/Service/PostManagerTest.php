<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Model\Post;

class PostManagerTest extends \WP_UnitTestCase {
	/** @var PostManager */
	private $_postManager;
	/** @var \WordPressHMVC\Post\Factory\PostFactory */
	private $_postMockFactory;
	/** @var null|\WP_Post */
	private $_originalGlobalPost;

	public function setUp() {
		global $post;
		parent::setUp();

		$this->_originalGlobalPost = $post;
		$this->_postMockFactory    = $this->getMockBuilder( 'WordPressHMVC\Post\Factory\PostFactory' )
		                                  ->disableOriginalConstructor()
		                                  ->getMock();
		$this->_postManager        = new PostManager( $this->_postMockFactory );
	}

	public function tearDown() {
		global $post;
		$post = $this->_originalGlobalPost;
	}

	/**
	 * Test retrieving an previous post will throw exception when global post not set
	 */
	public function testGetPreviousPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getPreviousPost();
	}

	/**
	 * Test retrieving previous post will throw exception when there is only one post
	 */
	public function testGetPreviousPost_When_No_Previous_Post() {
		global $post;
		$post = $this->factory->post->create_and_get();
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getPreviousPost();
	}

	/**
	 * Test retrieving previous post when no previous post exists
	 */
	public function testGetPreviousPost_When_Previous_Post_Exists() {
		global $post;
		$prevWpPostId = $this->factory->post->create(
			array(
				'post_date'   => '2010-01-01 00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );
		$post         = $this->factory->post->create_and_get( array(
			'post_date'   => '2010-01-02 00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );

		$this->_mockCreatePost( array( 'id' => $prevWpPostId ) );

		$adjacentPost = $this->_postManager->getPreviousPost();
		$this->assertEquals( $prevWpPostId, $adjacentPost->getId() );
	}

	/**
	 * Test retrieving an previous post will throw exception when global post not set
	 */
	public function testGetNextPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getNextPost();
	}

	/**
	 * Test retrieving previous post will throw exception when there is only one post
	 */
	public function testGetNextPost_When_No_Previous_Post() {
		global $post;
		$post = $this->factory->post->create_and_get();
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getNextPost();
	}

	public function testGetNextPost_When_Next_Post_Exists() {
		global $post;
		$post       = $this->factory->post->create_and_get( array(
			'post_date'   => '2010-01-01 00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );
		$nextPostId = $this->factory->post->create(
			array(
				'post_date'   => '2010-01-02 00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );

		$this->_mockCreatePost( array( 'id' => $nextPostId ) );

		$adjacentPost = $this->_postManager->getNextPost();
		$this->assertEquals( $nextPostId, $adjacentPost->getId() );
	}

	/**
	 * Test retrieving the current post when one exists
	 */
	public function testGetCurrentPost_When_Post_Exists() {
		global $post;
		$post = $this->factory->post->create_and_get();
		$this->_mockCreatePost( array( 'id' => $post->ID ) );
		$currentPost = $this->_postManager->getCurrentPost();
		$this->assertEquals( $post->ID, $currentPost->getId() );
	}

	/**
	 * Test retrieving the current post when none exists
	 */
	public function  testGetCurrentPost_When_Post_Not_Exists() {
		$this->setExpectedException( 'WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getCurrentPost();
	}

	/**
	 * Test retrieving a post that does exist
	 */
	public function testGetPost_When_Post_Exists() {
		$postId = $this->factory->post->create();
		$this->_mockCreatePost( array( 'id' => $postId ) );
		$currentPost = $this->_postManager->getPost( $postId );
		$this->assertEquals( $postId, $currentPost->getId() );
	}

	/**
	 * Test retrieving a post for an id that does not exist
	 */
	public function testGetPost_When_Post_Not_Exists() {
		$this->setExpectedException( 'WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getPost( 99 );
	}

	/**
	 * Mock the create method of the post factory with post data
	 *
	 * @param array $postData
	 */
	private function _mockCreatePost( $postData ) {
		$post = new Post();
		foreach ( $postData as $key => $data ) {
			$methodName = 'set' . ucfirst( $key );
			$post->{$methodName}( $data );
		}
		$this->_postMockFactory->method( 'create' )
		                       ->willReturn( $post );
	}
}
