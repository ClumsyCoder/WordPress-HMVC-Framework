<?php

namespace WordPressSolid\Post\Collection;

use Kozz\Components\Collection\Collection;
use WordPressSolid\Post\Model\Page;

/**
 * Class PageList
 * @package WordPressHMVC\Post\Collection
 */
class PageList extends Collection {
	/**
	 * @param Page $page
	 *
	 * @return PostList
	 */
	public function getChildren( Page $page ) {
		$parentPageId = $page->getId();
		$childPages   = array();
		/** @var Page $item */
		foreach ( $this->container as $item ) {
			if ( $item->getParentId() == $parentPageId ) {
				$childPages[] = $item;
			}
		}

		return new PostList( new \ArrayObject( $childPages ) );
	}

	/**
	 * @param int $pageId
	 */
	public function sortHierarchy( $pageId = 0 ) {
		if ( empty( $this->container ) ) {
			return;
		}

		/** @var Page $item */
		$pageBuckets = array();
		foreach ( $this->container as $item ) {
			$pageBuckets[ $item->getParentId() ][] = $item;
		}

		$result = array();
		$this->_sortHierarchyBuckets( $pageId, $pageBuckets, $result );
		$this->container = new \ArrayObject( $result );
	}

	/**
	 * @param int   $pageId
	 * @param array $children
	 * @param array $result
	 */
	private function _sortHierarchyBuckets( $pageId, &$children, &$result ) {
		if ( isset( $children[ $pageId ] ) ) {
			/** @var Page $child */
			foreach ( (array) $children[ $pageId ] as $child ) {
				$result[ $child->getId() ] = $child;
				$this->_sortHierarchyBuckets( $child->getId(), $children, $result );
			}
		}
	}
}