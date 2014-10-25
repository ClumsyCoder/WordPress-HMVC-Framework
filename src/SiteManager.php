<?php

namespace WordPressHMVC;

/**
 * Class SiteManager
 * @package WordPressHMVC
 */
class SiteManager {
	/**
	 * @param string $filter How to filter what is retrieved.
	 *
	 * @return string Name of the site
	 */
	public function getName( $filter = 'raw' ) {
		return get_bloginfo( 'name', $filter );
	}
} 