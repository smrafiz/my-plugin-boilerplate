<?php
/**
 * Functions Class: Helpers.
 *
 * List of all helper functions.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Functions;

/**
 * Class: Helpers.
 *
 * @since 1.0.0
 */
class Helpers {

	/**
	 * Method to beautify string.
	 *
	 * @static
	 *
	 * @param string $string String to beautify.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function beautify( $string ) {
		return ucwords( str_replace( '_', ' ', $string ) );
	}

	/**
	 * Method to uglify string.
	 *
	 * @static
	 *
	 * @param string $string String to uglify.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function uglify( $string ) {
		return strtolower( str_replace( ' ', '_', $string ) );
	}

	/**
	 * Method to Pluralize string.
	 *
	 * @static
	 *
	 * @param string $string String to Pluralize.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function pluralize( $string ) {
		$last = $string[ strlen( $string ) - 1 ];

		if ( 'y' === $last ) {
			$cut = substr( $string, 0, -1 );
			// convert y to ies.
			$plural = $cut . 'ies';
		} elseif ( 's' === $last ) {
			return $string;
		} else {
			// just attach an s.
			$plural = $string . 's';
		}

		return $plural;
	}
}
