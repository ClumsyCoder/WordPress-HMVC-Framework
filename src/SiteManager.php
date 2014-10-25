<?php

namespace WordPressHMVC;

class SiteManager {
	public function getName( $filter = 'raw' ) {
		get_bloginfo( 'name', $filter );
	}
} 