<?php

namespace WordPressHMVC\Theme;

/**
 * Class ImageSize
 * @package WordPressHMVC\Theme
 */
class ImageSize {
	/** @var string */
	private $_name;
	/** @var int */
	private $_width;
	/** @var int */
	private $_height;
	/** @var bool|array */
	private $_crop;

	/**
	 * @param string $name Image size identifier.
	 * @param int $width Image width in pixels.
	 * @param int $height Image height in pixels.
	 * @param bool|array $crop Whether to crop images to specified height and width or resize.
	 *                         An array can specify positioning of the crop area. Default false.
	 */
	public function __construct( $name, $width = 0, $height = 0, $crop = false ) {
		$this->_name   = $name;
		$this->_width  = $width;
		$this->_height = $height;
		$this->_crop   = $crop;
		add_image_size( $name, $width, $height, $crop );
	}

	/**
	 * @return array|bool
	 */
	public function getCrop() {
		return $this->_crop;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->_height;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->_width;
	}
}
