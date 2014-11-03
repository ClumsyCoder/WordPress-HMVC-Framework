<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Model\Post;

class PostManagerTest extends \WP_UnitTestCase {
	/** @var PostManager */
	private $_postManager;
	/** @var \WordPressHMVC\Post\Factory\PostFactory */
	private $_postMockFactory;
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
	 * Test retrieving the current post when one exists
	 */
	public function testGetCurrentPost_When_Post_Exists() {
		global $post;
		$postId = $this->factory->post->create();
		$post   = get_post( $postId );
		$this->_mockCreatePost( array( 'id' => $postId ) );
		$currentPost = $this->_postManager->getCurrentPost();
		$this->assertEquals( $currentPost->getId(), $postId );
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
		$this->assertEquals( $currentPost->getId(), $postId );
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
