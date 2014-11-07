<?php

namespace WordPressSolid;

/**
 * Class SiteManager
 * @package WordPressHMVC
 */
class SiteManager {
	/** @var \DateTimeZone */
	private $_timezone;
	/** @var \DateTimeZone */
	private $_gmtTimezone;

	/**
	 * @param string $filter How to filter what is retrieved.
	 *
	 * @return string Name of the site
	 */
	public function getName( $filter = 'raw' ) {
		return get_bloginfo( 'name', $filter );
	}

	public function getTimezone() {
		if ( ! $this->_timezone instanceof \DateTimeZone ) {
			$this->_timezone = new \DateTimeZone( get_option( 'timezone_string' ) );
		}

		return $this->_timezone;
	}

	public function getGmtTimezone() {
		if ( ! $this->_gmtTimezone instanceof \DateTimeZone ) {
			$this->_gmtTimezone = new \DateTimeZone( 'GMT' );
		}

		return $this->_gmtTimezone;
	}
} 