<?php

namespace WordPressSolid\Theme;

/**
 * Class Sidebar
 * @package WordPressHMVC\Theme
 */
class DynamicSidebar {
	/** @var string */
	private $_id;
	/** @var string */
	private $_name;
	/** @var string */
	private $_description;
	/** @var string */
	private $_class;
	/** @var string */
	private $_beforeWidget;
	/** @var string */
	private $_afterWidget;
	/** @var string */
	private $_beforeTitle;
	/** @var string */
	private $_afterTitle;

	/**
	 * @param string $id
	 * @param string $name
	 * @param string $description
	 * @param string $class
	 * @param string $beforeWidget
	 * @param string $afterWidget
	 * @param string $beforeTitle
	 * @param string $afterTitle
	 */
	public function __construct(
		$id, $name, $description = '', $class = '',
		$beforeWidget = '<li id="%1$s" class="widget %2$s">', $afterWidget = "</li>\n",
		$beforeTitle = '<h2 class="widgettitle">', $afterTitle = "</h2>\n"
	) {
		$this->_id           = $id;
		$this->_name         = $name;
		$this->_description  = $description;
		$this->_class        = $class;
		$this->_beforeTitle  = $beforeTitle;
		$this->_afterTitle   = $afterTitle;
		$this->_beforeWidget = $beforeWidget;
		$this->_afterWidget  = $afterWidget;
	}

	/**
	 * @return string
	 */
	public function getAfterTitle() {
		return $this->_afterTitle;
	}

	/**
	 * @return string
	 */
	public function getAfterWidget() {
		return $this->_afterWidget;
	}

	/**
	 * @return string
	 */
	public function getBeforeTitle() {
		return $this->_beforeTitle;
	}

	/**
	 * @return string
	 */
	public function getBeforeWidget() {
		return $this->_beforeWidget;
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->_class;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->_name;
	}
}