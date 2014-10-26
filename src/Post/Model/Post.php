<?php

namespace WordPressHMVC\Post\Model;

/**
 * Class Post
 * @package WordPressHMVC\Post\Model
 */
class Post {
	/** @var int */
	private $_id;
	/** @var string */
	private $_name;
	/** @var int */
	private $_authorId;
	/** @var \DateTime */
	private $_publicationDate;
	/** @var \DateTime */
	private $_publicationGmtDate;
	/** @var \DateTime */
	private $_modifiedDate;
	/** @var \DateTime */
	private $_modifiedGmtDate;
	/** @var string */
	private $_content;
	/** @var string */
	private $_title;
	/** @var string */
	private $_excerpt;
	/** @var string */
	private $_status;
	/** @var string */
	private $_commentStatus;
	/** @var int */
	private $_commentCount;
	/** @var string */
	private $_password;

	/**
	 * @param mixed $authorId
	 */
	public function setAuthorId( $authorId ) {
		$this->_authorId = $authorId;
	}

	/**
	 * @param mixed $commentStatus
	 */
	public function setCommentStatus( $commentStatus ) {
		$this->_commentStatus = $commentStatus;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->_content = $content;
	}

	/**
	 * @param mixed $excerpt
	 */
	public function setExcerpt( $excerpt ) {
		$this->_excerpt = $excerpt;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->_id = $id;
	}

	/**
	 * @param mixed $modifiedDate
	 */
	public function setModifiedDate( $modifiedDate ) {
		$this->_modifiedDate = $modifiedDate;
	}

	/**
	 * @param mixed $modifiedGmtDate
	 */
	public function setModifiedGmtDate( $modifiedGmtDate ) {
		$this->_modifiedGmtDate = $modifiedGmtDate;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->_name = $name;
	}

	/**
	 * @param mixed $publicationDate
	 */
	public function setPublicationDate( $publicationDate ) {
		$this->_publicationDate = $publicationDate;
	}

	/**
	 * @param mixed $publicationGmtDate
	 */
	public function setPublicationGmtDate( $publicationGmtDate ) {
		$this->_publicationGmtDate = $publicationGmtDate;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus( $status ) {
		$this->_status = $status;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->_title = $title;
	}

	/**
	 * @param int $commentCount
	 */
	public function setCommentCount( $commentCount ) {
		$this->_commentCount = $commentCount;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password ) {
		$this->_password = $password;
	}
}