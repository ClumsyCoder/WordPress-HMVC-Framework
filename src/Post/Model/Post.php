<?php

namespace WordPressHMVC\Post\Model;

use WordPressHMVC\Post\Exception\FormatNotSet;
use WordPressHMVC\Post\Exception\PostNotExist;

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

	/**
	 * Retrieve full permalink for this post.
	 *
	 * @param bool $leaveName Optional. Whether to keep post name or page name. Default false.
	 *
	 * @return string
	 */
	public function getPermalink( $leaveName = false ) {
		$result = get_permalink( $this->_id, $leaveName );
		if ( $result === false ) {
			throw new PostNotExist();
		}

		return $result;
	}

	/**
	 * Retrieve the format slug
	 *
	 * @return string
	 */
	public function getFormat() {
		$result = get_post_format( $this->_id );
		if ( $result == false ) {
			throw new FormatNotSet();
		}

		return $result;
	}

	/**
	 * Check if a post has any of the given formats, or any format.
	 *
	 * @param array $format Optional. The format or formats to check.
	 *
	 * @return bool
	 */
	public function hasFormat( $format = array() ) {
		return has_post_format( $format, $this->_id );
	}

	/**
	 * Assign a format.
	 *
	 * @param array $format A format to assign. Use an empty string or array to remove all formats from the post.
	 */
	public function setFormat( array $format ) {
		$result = set_post_format( $this->_id, $format );
		if ( is_wp_error( $result ) ) {
			throw new PostNotExist();
		}
	}

	/**
	 * Retrieve edit posts link for post.
	 *
	 * @param string $context Optional, defaults to display. How to write the '&', defaults to '&amp;'.
	 *
	 * @return string
	 */
	public function getEditLink( $context = 'display' ) {
		$result = get_edit_post_link( $this->_id, $context );
		if ( empty( $result ) ) {
			throw new PostNotExist();
		}

		return $result;
	}

	/**
	 * Retrieve delete posts link for post.
	 *
	 * @return string
	 */
	public function getDeleteLink() {
		$result = get_delete_post_link( $this->_id );
		if ( empty( $result ) ) {
			throw new PostNotExist();
		}

		return $result;
	}

	public function isSticky() {
		return is_sticky( $this->_id );
	}
}