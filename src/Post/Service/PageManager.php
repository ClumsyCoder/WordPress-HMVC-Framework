<?php

namespace WordPressHMVC\Post\Service;

use WordPressHMVC\Post\Exception\PageNotExist;
use WordPressHMVC\Post\Factory\PageFactory;
use WordPressHMVC\Post\Model\Page;

class PageManager {
	/** @var PageFactory */
	private $_pageFactory;

	/**
	 * @param PageFactory $pageFactory
	 */
	public function __construct( PageFactory $pageFactory ) {
		$this->_pageFactory = $pageFactory;
	}

	/**
	 * @return array
	 */
	public function getAllPageIds() {
		return get_all_page_ids();
	}

	/**
	 * @param Page $page
	 *
	 * @return array
	 */
	public function getPageAncestors( Page $page ) {
		return get_ancestors( $page->getId(), 'page' );
	}

	/**
	 * @param $path
	 *
	 * @return Page
	 */
	public function getPageByPath( $path ) {
		$page = get_page_by_path( $path );
		if ( empty( $page ) ) {
			throw new PageNotExist();
		}

		return $this->_pageFactory->create( $page );
	}
}