<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Model\Post;

class PostManagerTest extends \WP_UnitTestCase {
	/** @var PostManager */
	private $_postManager;
	/** @var \WordPressHMVC\Post\Factory\PostFactory */
	private $_postMockFactory;

	public function setUp() {
		parent::setUp();

		$this->_postMockFactory = $this->getMockBuilder( 'WordPressHMVC\Post\Factory\PostFactory' )
		                               ->disableOriginalConstructor()
		                               ->getMock();
		$this->_postManager     = new PostManager( $this->_postMockFactory );
	}

	public function testGetCurrentPost_When_Post_Exists() {
		global $post;
		$postId = $this->factory->post->create();
		$post   = get_post( $postId );

		$returnPost = new Post();
		$returnPost->setId( $postId );
		$this->_postMockFactory->method( 'create' )
		                       ->willReturn( $returnPost );
		$currentPost = $this->_postManager->getCurrentPost();

		$this->assertEquals( $currentPost->getId(), $postId );
	}

	public function  testGetCurrentPost_When_Post_Not_Exists() {
		$this->setExpectedException( 'WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getCurrentPost();
	}

	public function testGetPost_When_Post_Exists() {
		$postId = $this->factory->post->create();

		$returnPost = new Post();
		$returnPost->setId( $postId );
		$this->_postMockFactory->method( 'create' )
		                       ->willReturn( $returnPost );

		$currentPost = $this->_postManager->getPost( $postId );

		$this->assertEquals( $currentPost->getId(), $postId );
	}

	public function testGetPost_When_Post_Not_Exists() {
		$this->setExpectedException( 'WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getPost( 99 );
	}
}
