<?php

namespace WordPressHMVC\Post\Model;

class Page {
	/** @var int */
	private $_id;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->_id = absint( $id );
	}

	/**
	 * @param bool $leaveName Optional. Whether to keep page name.
	 *
	 * @return string
	 */
	public function getPermalink( $leaveName = false ) {
		return get_page_link( $this->_id, $leaveName );
	}

	/**
	 * @param bool $leaveName Optional. Whether to keep page name.
	 *
	 * @return string
	 */
	public function getSamplePermalink( $leaveName = false ) {
		return get_page_link( $this->_id, $leaveName, true );
	}
} 