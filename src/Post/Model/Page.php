<?php

namespace WordPressSolid\Post\Model;

use WordPressSolid\Post\Exception\PageNotExist;

class Page {
	/** @var int */
	private $_id;
	/** @var int */
	private $_parentId = 0;

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
	 * @return int
	 */
	public function getParentId() {
		return $this->_parentId;
	}

	/**
	 * @param int $parentId
	 */
	public function setParentId( $parentId ) {
		$this->_parentId = absint( $parentId );
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

	/**
	 * @return string
	 */
	public function getUri() {
		$uri = get_page_uri( $this->_id );
		if ( empty( $uri ) ) {
			throw new PageNotExist();
		}

		return $uri;
	}
} 