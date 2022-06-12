<?php
/**
 * Functions Class: Filters.
 *
 * List of all functions hooked in filter hooks.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Functions;

/**
 * Class: Filters.
 *
 * @since 1.0.0
 */
class Filters {

	/**
	 * Testing hooked function.
	 *
	 * @static
	 *
	 * @param string $title Title.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function testFilter( $title ) {
		// $title = 'Add New';
		return $title;
	}
}
