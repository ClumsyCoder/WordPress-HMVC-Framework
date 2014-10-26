<?php

namespace WordPressHMVC\Theme;

class SidebarManager {
	public function showSidebar( $name ) {
		get_sidebar( $name );
	}

	public function showDynamicSidebar( $index = 1 ) {
		$arg = $index instanceof DynamicSidebar ? $index->getId() : $index;

		dynamic_sidebar( $arg );
	}

	public function registerDynamicSidebar( DynamicSidebar $sidebar ) {
		register_sidebar( $this->_getDynamicRegisterSidebarArguments( $sidebar ) );
	}

	public function registerDynamicSidebars( $number, DynamicSidebar $sidebar ) {
		register_sidebars( $number, $this->_getDynamicRegisterSidebarArguments( $sidebar ) );
	}

	public function unregisterDynamicSidebar( $sidebar ) {
		$sidebarId = $sidebar instanceof DynamicSidebar ? $sidebar->getId() : $sidebar;

		unregister_sidebar( $sidebarId );
	}

	public function activeDynamicSidebarExists() {
		return is_dynamic_sidebar();
	}

	public function isDynamicSidebarActive( $index ) {
		$arg = $index instanceof DynamicSidebar ? $index->getId() : $index;
		
		return is_active_sidebar( $arg );
	}

	private function _getDynamicRegisterSidebarArguments( DynamicSidebar $sidebar ) {
		return array(
			'name'          => $sidebar->getName(),
			'id'            => $sidebar->getId(),
			'description'   => $sidebar->getDescription(),
			'class'         => $sidebar->getClass(),
			'before_widget' => $sidebar->getBeforeWidget(),
			'after_widget'  => $sidebar->getAfterWidget(),
			'before_title'  => $sidebar->getBeforeTitle(),
			'after_title'   => $sidebar->getAfterTitle(),
		);
	}
} 