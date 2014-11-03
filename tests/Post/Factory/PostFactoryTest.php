<?php

namespace WordPressHMVC\Post\Factory;

use WordPressHMVC\Post\Model\Post;

class PostFactoryTest extends \WP_UnitTestCase {
	public function testCreate_When_Given_WP_Post() {
		$mockSiteManager = $this->getMockBuilder( '\WordPressHMVC\SiteManager' )
		                        ->getMock();
		$mockSiteManager->method( 'getTimezone' )
		                ->willReturn( new \DateTimeZone( 'America/Toronto' ) );
		$mockSiteManager->method( 'getGmtTimezone' )
		                ->willReturn( new \DateTimeZone( 'GMT' ) );

		$wpPost = $this->factory->post->create_and_get();

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
		$expectedPost->setPublicationDate( new \DateTime( $wpPost->post_date, $mockSiteManager->getTimezone() ) );
		$expectedPost->setPublicationGmtDate( new \DateTime( $wpPost->post_date_gmt, $mockSiteManager->getGmtTimezone() ) );
		$expectedPost->setModifiedDate( new \DateTime( $wpPost->post_modified, $mockSiteManager->getTimezone() ) );
		$expectedPost->setModifiedGmtDate( new \DateTime( $wpPost->post_modified_gmt, $mockSiteManager->getGmtTimezone() ) );

		$factory = new PostFactory( $mockSiteManager );
		$post    = $factory->create( $wpPost );
		$this->assertEquals( $expectedPost, $post );
	}
}
