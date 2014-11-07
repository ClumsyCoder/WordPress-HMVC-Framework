<?php

namespace WordPressSolid\Post\Model;

class PostTest extends \WP_UnitTestCase {
	/** @var Post */
	private $_post;

	public function setUp() {
		parent::setUp();
		$this->_post = new Post();
	}

	public function testGetPermalink() {
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$permalink = $this->_post->getPermalink();
		$this->assertEquals( 'http://' . WP_TESTS_DOMAIN . '/?p=14', $permalink );
	}

	public function testGetPermalink_When_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_post->getPermalink();
	}

	public function testGetFormat() {
		$postId = $this->factory->post->create();
		set_post_format( $postId, 'gallery' );

		$this->_post->setId( $postId );
		$format = $this->_post->getFormat();
		$this->assertEquals( 'gallery', $format );
	}

	public function testHasFormat() {
		$postId = $this->factory->post->create();
		set_post_format( $postId, 'gallery' );

		$this->_post->setId( $postId );
		$this->assertTrue( $this->_post->hasFormat( 'gallery' ) );
		$this->assertFalse( $this->_post->hasFormat( 'image' ) );
	}

	public function testHasFormat_When_Post_Not_Exists() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_post->hasFormat( 'gallery' );
	}

	public function testSetFormat_When_Post_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_post->setFormat( 'gallery' );
	}

	public function testSetFormat() {
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$this->_post->setFormat( 'gallery' );

		$this->assertEquals( 'gallery', get_post_format( $postId ) );
	}

	public function testGetEditLink_When_Permission_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\NotAllowedToEdit' );
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$this->_post->getEditLink();
	}

	public function testGetEditLink_When_Post_Not_Exist() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_post->getEditLink();
	}

	public function testGetEditLink() {
		$user = $this->factory->user->create_and_get( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user->ID );
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$editLink = $this->_post->getEditLink();
		$this->assertEquals( 'http://' . WP_TESTS_DOMAIN . '/wp-admin/post.php?post=' . $postId . '&amp;action=edit', $editLink );
	}

	public function testGetDeleteLink_When_Permission_Not_Set() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\NotAllowedToDelete' );
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$this->_post->getDeleteLink();
	}

	public function testGetDeleteLink_When_Post_Not_Exist() {
		$this->setExpectedException( '\WordPressHMVC\Post\Exception\PostNotExist' );
		$this->_post->getDeleteLink();
	}

	public function testGetDeleteLink() {
		$user = $this->factory->user->create_and_get( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user->ID );
		$postId = $this->factory->post->create();
		$this->_post->setId( $postId );
		$deleteLink = $this->_post->getDeleteLink();
		$this->assertContains( 'http://' . WP_TESTS_DOMAIN . '/wp-admin/post.php?post=' . $postId, $deleteLink );
	}
}
