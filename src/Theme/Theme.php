<?php

namespace WordPressSolid;

use WordPressSolid\Theme\ImageSize;

/**
 * Class Theme
 * @package WordPressHMVC
 */
class Theme {
	/** @var array List of image sizes */
	private $_imageSizes = array();

	/**
	 * Load header template
	 *
	 * @param string $name
	 */
	public function showHeader( $name = null ) {
		get_header( $name );
	}

	/**
	 * Load footer template
	 *
	 * @param string $name
	 */
	public function showFooter( $name = null ) {
		get_footer( $name );
	}

	/**
	 * Display search form
	 */
	public function showSearchForm() {
		get_search_form( true );
	}

	/**
	 * Display search form
	 *
	 * @return string
	 */
	public function getSearchForm() {
		return get_search_form();
	}

	/**
	 * Load the comment template specified in $file
	 *
	 * @param string $file Optional. The file to load. Default '/comments.php'.
	 * @param bool $separateComments Optional. Whether to separate the comments by comment type.
	 *                               Default false.
	 */
	public function showCommentsTemplate( $file = '/comments.php', $separateComments = false ) {
		comments_template( $file, $separateComments );
	}

	/**
	 * @param ImageSize $imageSize
	 */
	public function addImageSize( ImageSize $imageSize ) {
		$this->_imageSizes[ $imageSize->getName() ] = $imageSize;
	}

	/**
	 * @param array $formats
	 */
	public function addPostFormats( array $formats ) {
		$this->_addSupport( 'post-formats', $formats );
	}

	/**
	 * @param array $postTypes
	 */
	public function addPostThumbnails( array $postTypes = array() ) {
		$this->_addSupport( 'post-thumbnails', $postTypes );
	}

	/**
	 * @param array $arguments
	 */
	public function addCustomBackground( array $arguments = array() ) {
		$this->_addSupport( 'custom-background', $arguments );
	}

	/**
	 * @param array $arguments
	 */
	public function addCustomHeader( array $arguments = array() ) {
		$this->_addSupport( 'custom-header', $arguments );
	}

	public function addFeedLinks() {
		$this->_addSupport( 'automatic-feed-links' );
	}

	/**
	 * @param array $arguments
	 */
	public function addHtml5Support( array $arguments ) {
		$this->_addSupport( 'html5', $arguments );
	}

	/**
	 * @param string $feature
	 * @param array $arguments
	 */
	private function _addSupport( $feature, $arguments = array() ) {
		if ( ! empty( $arguments ) ) {
			add_theme_support( $feature, $arguments );
		} else {
			add_theme_support( $feature );
		}
	}
}