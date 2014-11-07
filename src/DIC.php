<?php
namespace WordPressSolid;



/**
 * Class DIC
 * @package WordPressHMVC
 */
class DIC {

	/** @var DIC */
	private static $_instance;


	private function __construct() {

	}

	/**
	 * @return DIC
	 */
	public static function instance() {
		if ( ! static::$_instance instanceof DIC ) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	public function haha() {
		$this->_container[ self::SITE_MANAGER ] = function ( $c ) {
			return new SiteManager();
		};
		$this->_container[ self::THEME ]        = function ( $c ) {
			return new Theme();
		};
	}



} 