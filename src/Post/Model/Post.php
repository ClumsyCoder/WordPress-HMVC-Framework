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
	 * @param int $authorId
	 */
	public function setAuthorId( $authorId ) {
		$this->_authorId = absint( $authorId );
	}

	/**
	 * @param bool $commentStatus
	 */
	public function setCommentStatus( $commentStatus ) {
		$this->_commentStatus = ( $commentStatus == true );
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
	 * @return int
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->_id = absint( $id );
	}

	/**
	 * @param \DateTime $modifiedDate
	 */
	public function setModifiedDate( \DateTime $modifiedDate ) {
		$this->_modifiedDate = $modifiedDate;
	}

	/**
	 * @param \DateTime $modifiedGmtDate
	 */
	public function setModifiedGmtDate( \DateTime $modifiedGmtDate ) {
		$this->_modifiedGmtDate = $modifiedGmtDate;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->_name = $name;
	}

	/**
	 * @param \DateTime $publicationDate
	 */
	public function setPublicationDate( \DateTime $publicationDate ) {
		$this->_publicationDate = $publicationDate;
	}

	/**
	 * @param \DateTime $publicationGmtDate
	 */
	public function setPublicationGmtDate( \DateTime $publicationGmtDate ) {
		$this->_publicationGmtDate = $publicationGmtDate;
	}

	/**
	 * @param string $status
	 */
	public function setStatus( $status ) {
		$this->_status = $status;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title ) {
		$this->_title = $title;
	}

	/**
	 * @param int $commentCount
	 */
	public function setCommentCount( $commentCount ) {
		$this->_commentCount = absint( $commentCount );
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
		$this->_assertPostIdSet();
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
		$this->_assertPostIdSet();
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
		$this->_assertPostIdSet();

		return has_post_format( $format, $this->_id );
	}

	/**
	 * Assign a format.
	 *
	 * @param string $format A format to assign. Use an empty string or array to remove all formats from the post.
	 */
	public function setFormat( $format ) {
		$this->_assertPostIdSet();
		$result = set_post_format( $this->_id, $format );
		if ( is_wp_error( $result ) ) {
			throw new PostNotExist();
		}
	}

	/**
	 * Check if post is sticky.
	 *
	 * @return bool
	 */
	public function isSticky() {
		$this->_assertPostIdSet();

		return is_sticky( $this->_id );
	}

	/**
	 * Check if post has an image attached.
	 *
	 * @return bool
	 */
	public function hasThumbnail() {
		$this->_assertPostIdSet();

		return has_post_thumbnail( $this->_id );
	}

	/**
	 * Check if post has an excerpt.
	 *
	 * @return bool
	 */
	public function hasExcerpt() {
		$this->_assertPostIdSet();

		return has_excerpt( $this->_id );
	}

	/**
	 * @param string       $size Image size. Defaults to 'post-thumbnail'.
	 * @param string|array $attr Optional. Query string or array of attributes.
	 *
	 * @return mixed|void
	 */
	public function getThumbnail( $size = 'post-thumbnail', $attr = '' ) {
		$this->_assertPostIdSet();

		$thumbnailData = get_the_post_thumbnail( $this->_id, $size, 'attr' );

		//todo: should return an attachment object instead
		return $thumbnailData;
	}

	/**
	 * Asserts that the current post exists.  If not an exception PostNotExist is thrown.
	 */
	private function _assertPostIdSet() {
		if ( ! isset( $this->_id ) ) {
			throw new PostNotExist();
		}
	}
}