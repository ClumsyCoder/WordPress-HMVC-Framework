<?php

namespace WordPressSolid;

use DateTimeZone;

class Date extends \DateTime {
	private static $_GMT_TIMEZONE;
	private static $_SITE_TIMEZONE_STRING = '';
	private static $_SITE_TIMEZONE;

	/**
	 * @param string       $time
	 * @param DateTimeZone $timezone
	 */
	public function __construct( $time = 'now', DateTimeZone $timezone = null ) {
		if ( $timezone == null ) {
			$timezone = self::_getSiteTimezone();
		}
		parent::__construct( $time, $timezone );
	}

	/**
	 * @param string $dateString
	 *
	 * @return Date
	 */
	public static function getDate( $dateString ) {
		return new Date( $dateString );
	}

	/**
	 * @param string $dateString
	 *
	 * @return Date
	 */
	public static function getGmtDate( $dateString ) {
		return new Date( $dateString, self::_getGmtTimezone() );
	}

	/**
	 * @return \DateTimeZone
	 */
	private static function _getSiteTimezone() {
		$timezoneString = get_option( 'timezone_string' );
		if ( $timezoneString != self::$_SITE_TIMEZONE_STRING ) {
			self::$_SITE_TIMEZONE_STRING = $timezoneString;
			self::$_SITE_TIMEZONE        = new \DateTimeZone( $timezoneString );
		}

		return self::$_SITE_TIMEZONE;
	}

	/**
	 * @return \DateTimeZone
	 */
	private static function _getGmtTimezone() {
		if ( ! self::$_GMT_TIMEZONE instanceof \DateTimeZone ) {
			self::$_GMT_TIMEZONE = new \DateTimeZone( 'GMT' );
		}

		return self::$_GMT_TIMEZONE;
	}
}