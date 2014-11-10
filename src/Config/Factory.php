<?php
/**
 * Created by PhpStorm.
 * User: rburnfield
 * Date: 11/10/2014
 * Time: 11:57 AM
 */

namespace WordPressSolid\Config;

use Zend\Config\Config as ZendConfig;

class Factory extends \Zend\Config\Factory {
	/**
	 * Read a config from a file.
	 *
	 * @param string $filename
	 * @param bool   $returnConfigObject
	 * @param bool   $useIncludePath
	 *
	 * @return array|Config|ZendConfig
	 */
	public static function fromFile( $filename, $returnConfigObject = false, $useIncludePath = false ) {
		$config = parent::fromFile( $filename, $returnConfigObject, $useIncludePath );

		if ( $returnConfigObject ) {
			$config = self::_convertZendConfigToWordPressSolidConfig( $config );
		}

		return $config;
	}

	/**
	 * Read configuration from multiple files and merge them.
	 *
	 * @param array $files
	 * @param bool  $returnConfigObject
	 * @param bool  $useIncludePath
	 *
	 * @return array|Config|ZendConfig
	 */
	public static function fromFiles( array $files, $returnConfigObject = false, $useIncludePath = false ) {
		$config = parent::fromFiles( $files, $returnConfigObject, $useIncludePath );

		if ( $returnConfigObject ) {
			$config = self::_convertZendConfigToWordPressSolidConfig( $config );
		}

		return $config;
	}

	/**
	 * Convert a zend config object into a WordPressSolid Config object
	 *
	 * @param ZendConfig $config
	 *
	 * @return Config
	 */
	private static function _convertZendConfigToWordPressSolidConfig( ZendConfig $config ) {
		return new Config( $config->toArray() );
	}
}