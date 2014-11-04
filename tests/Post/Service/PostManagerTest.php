<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Collection\PostList;
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

	public function testGetFirstPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getFirstPost();
	}

	public function testGetFirstPost_When_First_Post_Not_Exist() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_createGlobalPost( array( 'post_type' => 'page' ) );
		$this->_postManager->getFirstPost();
	}

	public function testGetFirstPost_When_First_Post_Exists() {
		$firstPostId = $this->factory->post->create(
			array(
				'post_date'   => '2010-01-01 00:00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );
		$this->factory->post->create(
			array(
				'post_date'   => '2010-01-02 00:00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );
		$this->_createGlobalPost( array(
			'post_date'   => '2010-01-03 00:00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );

		$this->_mockCreatePost( array( 'id' => $firstPostId ) );
		$firstPost = $this->_postManager->getFirstPost();
		$this->assertEquals( $firstPostId, $firstPost->getId() );
	}

	public function getLastPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getLastPost();
	}

	public function testGetLastPost_When_First_Post_Not_Exist() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_createGlobalPost( array( 'post_type' => 'page' ) );
		$this->_postManager->getLastPost();
	}

	public function testGetLastPost_When_First_Post_Exists() {
		$this->factory->post->create(
			array(
				'post_date'   => '2010-01-02 00:00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );
		$this->_createGlobalPost( array(
			'post_date'   => '2010-01-03 00:00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );
		$lastPostId = $this->factory->post->create(
			array(
				'post_date'   => '2010-01-01 00:00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );

		$this->_mockCreatePost( array( 'id' => $lastPostId ) );
		$firstPost = $this->_postManager->getLastPost();
		$this->assertEquals( $lastPostId, $firstPost->getId() );
	}

	public function testGetPreviousPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getPreviousPost();
	}

	public function testGetPreviousPost_When_No_Previous_Post() {
		$this->_createGlobalPost();
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getPreviousPost();
	}

	public function testGetPreviousPost_When_Previous_Post_Exists() {
		$prevWpPostId = $this->factory->post->create(
			array(
				'post_date'   => '2010-01-01 00:00',
				'post_status' => 'publish',
				'post_type'   => 'post'
			) );
		$this->_createGlobalPost( array(
			'post_date'   => '2010-01-02 00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );

		$this->_mockCreatePost( array( 'id' => $prevWpPostId ) );

		$adjacentPost = $this->_postManager->getPreviousPost();
		$this->assertEquals( $prevWpPostId, $adjacentPost->getId() );
	}

	public function testGetNextPost_When_Global_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\GlobalPostNotSet' );
		$this->_postManager->getNextPost();
	}

	public function testGetNextPost_When_No_Previous_Post() {
		$this->_createGlobalPost();
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getNextPost();
	}

	public function testGetNextPost_When_Next_Post_Exists() {
		$this->_createGlobalPost( array(
			'post_date'   => '2010-01-01 00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );
		$nextPostId = $this->factory->post->create( array(
			'post_date'   => '2010-01-02 00:00',
			'post_status' => 'publish',
			'post_type'   => 'post'
		) );

		$this->_mockCreatePost( array( 'id' => $nextPostId ) );

		$adjacentPost = $this->_postManager->getNextPost();
		$this->assertEquals( $nextPostId, $adjacentPost->getId() );
	}

	public function testGetCurrentPost_When_Post_Exists() {
		$postId = $this->_createGlobalPost();
		$this->_mockCreatePost( array( 'id' => $postId ) );
		$currentPost = $this->_postManager->getCurrentPost();
		$this->assertEquals( $postId, $currentPost->getId() );
	}

	public function  testGetCurrentPost_When_Post_Not_Exists() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getCurrentPost();
	}

	public function testGetPost_When_Post_Exists() {
		$postId = $this->factory->post->create();
		$this->_mockCreatePost( array( 'id' => $postId ) );
		$currentPost = $this->_postManager->getPost( $postId );
		$this->assertEquals( $postId, $currentPost->getId() );
	}

	public function testGetPost_When_Post_Not_Exists() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_postManager->getPost( 99 );
	}

	public function testGetPosts_When_No_Post_Exists() {
		$posts = $this->_postManager->getPosts();
		$this->assertEmpty( $posts );
	}

	public function testGetPosts_When_Posts_Set() {
		$posts            = array(
			$this->_createAndGetGlobalPost(),
		);
		$expectedPostList = new PostList( new \ArrayObject( $posts ) );
		$this->_postMockFactory->method( 'createList' )
		                       ->willReturn( $expectedPostList );
		$posts = $this->_postManager->getPosts();
		$this->assertNotEmpty( $posts );
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

	/**
	 * @param array $arguments
	 *
	 * @return null|\WP_Post
	 */
	private function _createAndGetGlobalPost( $arguments = array() ) {
		$post = $this->factory->post->create_and_get( $arguments );
		$this->go_to( get_permalink( $post->ID ) );

		return $post;
	}

	/**
	 * @param array $arguments
	 *
	 * @return int
	 */
	private function _createGlobalPost( $arguments = array() ) {
		$post = $this->_createAndGetGlobalPost( $arguments );

		return $post->ID;
	}
}
