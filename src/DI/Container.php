<?php

namespace WordPressSolid\DI;

class Container extends \Pimple\Container {
	/** @var string Id parameter of the most recent offsetGet that was called */
	private $_callingId;

	/**
	 * Gets a parameter or an object.
	 *
	 * @param string $id
	 *
	 * @return mixed
	 */
	public function offsetGet( $id ) {
		$this->_callingId = $id;

		return parent::offsetGet( $id );
	}

	/**
	 * @return string
	 */
	public function getLastCalledId() {
		return $this->_callingId;
	}
}