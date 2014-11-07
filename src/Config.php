<?php

namespace WordPressSolid;

class Config {
	/** @var mixed */
	private $_rawConfig;

	/**
	 * @param string $config Path to the configuration file
	 */
	public function __construct( $config ) {
		if ( is_array( $config ) ) {
			$this->_rawConfig = $config;
		} elseif ( is_file( $config ) ) {
			$this->_rawConfig = include( $config );
		} else {
			throw new \RuntimeException( "The config provided '{$config}' is not supported" );
		}
	}

	public function getServices() {
		return isset( $this->_rawConfig['services'] ) ? $this->_rawConfig['services'] : array();
	}
}