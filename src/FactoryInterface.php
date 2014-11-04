<?php

namespace WordPressHMVC;

interface FactoryInterface {
	/**
	 * @param mixed $data
	 *
	 * @return mixed
	 */
	public function create( $data );

	/**
	 * @param array $dataList
	 *
	 * @return mixed
	 */
	public function createList( array $dataList );
}