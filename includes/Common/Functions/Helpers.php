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

	/**
	 * Gets Ajax URL.
	 *
	 * @static
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function ajaxUrl() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Nonce Text.
	 *
	 * @static
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function nonceText() {
		return 'my_plugin_boilerplate_nonce_secret';
	}

	/**
	 * Nonce ID.
	 *
	 * @static
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function nonceId() {
		return 'my_plugin_boilerplate_nonce';
	}

	/**
	 * Creates Nonce.
	 *
	 * @static
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public static function createNonce() {
		wp_nonce_field( self::nonceText(), self::nonceId() );
	}

	/**
	 * Verifies the Nonce.
	 *
	 * @static
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function verifyNonce() {
		$nonce     = null;
		$nonceText = self::nonceText();

		if ( isset( $_REQUEST[ self::nonceId() ] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST[ self::nonceId() ] ) );
		}

		if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Allowed Tags for wp_kses: Basic.
	 *
	 * @static
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function allowedTags() {
		return [
			'a'          => [
				'class' => [],
				'href'  => [],
				'rel'   => [],
				'title' => [],
			],
			'b'          => [],
			'blockquote' => [
				'cite' => [],
			],
			'cite'       => [
				'title' => [],
			],
			'code'       => [],
			'div'        => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'em'         => [],
			'h1'         => [
				'class' => [],
			],
			'h2'         => [
				'class' => [],
			],
			'h3'         => [
				'class' => [],
			],
			'h4'         => [
				'class' => [],
			],
			'h5'         => [
				'class' => [],
			],
			'h6'         => [
				'class' => [],
			],
			'i'          => [
				'class' => [],
			],
			'img'        => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'         => [
				'class' => [],
			],
			'ol'         => [
				'class' => [],
			],
			'p'          => [
				'class' => [],
			],
			'span'       => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'strong'     => [],
			'ul'         => [
				'class' => [],
			],
		];
	}

	/**
	 * Sanitize field value
	 *
	 * @static
	 *
	 * @param array $field Meta Fields.
	 * @param mixed $value Value to sanitize.
	 *
	 * @return mixed
	 * @since  1.0.0
	 */
	public static function sanitize( $field = [], $value = null ) {
		if ( ! is_array( $field ) ) {
			return;
		}

		$sanitizedValue = null;

		$type = ( ! empty( $field['type'] ) ? $field['type'] : 'text' );

		if ( 'number' === $type || 'select' === $type || 'checkbox' === $type || 'radio' === $type ) {
			$sanitizedValue = sanitize_text_field( $value );
		} elseif ( 'text' === $type ) {
			$sanitizedValue = wp_kses( $value, self::allowedTags() );
		} elseif ( 'url' === $type ) {
			$sanitizedValue = esc_url( $value );
		} elseif ( 'textarea' === $type ) {
			$sanitizedValue = wp_kses_post( $value );
		} elseif ( 'color' === $type ) {
			$sanitizedValue = self::sanitizeHexColor( $value );
		} else {
			$sanitizedValue = sanitize_text_field( $value );
		}

		return $sanitizedValue;
	}

	/**
	 * Sanitizes Hex Color.
	 *
	 * @param string $color Hex Color.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function sanitizeHexColor( $color ) {
		if ( function_exists( 'sanitize_hex_color' ) ) {
			return sanitize_hex_color( $color );
		} else {
			if ( '' === $color ) {
				return '';
			}

			// 3 or 6 hex digits, or the empty string.
			if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
				return $color;
			}
		}
	}

	/**
	 * Renders Admin View.
	 *
	 * @param string $viewName View name.
	 * @param array  $args View args.
	 *
	 * @return void|string
	 * @since  1.0.0
	 */
	public static function renderView( $viewName, $args = [] ) {
		$file       = str_replace( '.', '/', $viewName );
		$file       = ltrim( $file, '/' );
		$pluginPath = \my_plugin_boilerplate()->getData()['plugin_path'];
		$viewsPath  = \my_plugin_boilerplate()->getData()['views_folder'];
		$viewFile   = trailingslashit( $pluginPath . '/' . $viewsPath ) . $file . '.php';

		if ( ! file_exists( $viewFile ) ) {
			return new \WP_Error(
				'brock',
				/* translators: View file name. */
				sprintf( __( '%s file not found', 'my-plugin-text-domain' ), $viewFile )
			);
		}

		load_template( $viewFile, true, $args );
	}
}
