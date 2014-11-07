<?php

namespace WordPressSolid;

class Config {
	/** @var array */
	private $_configArray;

	/**
	 * @param string $config Path to the configuration file
	 */
	public function __construct( $config ) {
		if ( is_array( $config ) ) {
			$this->_configArray = $config;
		} elseif ( is_file( $config ) ) {
			$this->_configArray = include( $config );
		} else {
			throw new \RuntimeException( "The config provided '{$config}' is not supported" );
		}
	}

	public function getServices() {
		return isset( $this->_configArray['services'] ) ? $this->_configArray['services'] : array();
	}

	public function merge( Config $newConfig ) {
		$this->_configArray = array_merge_recursive( $newConfig->toArray(), $this->_configArray );
	}

	public function toArray() {
		return is_array( $this->_configArray ) ? $this->_configArray : array();
	}
}