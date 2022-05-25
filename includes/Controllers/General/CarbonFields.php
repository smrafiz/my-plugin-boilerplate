<?php
/**
 * General Class: Carbon Fields.
 *
 * This class initializes Carbon Fields.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

namespace Prefix\MyPluginBoilerplate\Controllers\General;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * General Class: Carbon Fields.
 *
 * @since 1.0.0
 */
class CarbonFields extends Base {

	/**
	 * Singleton Trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Registers the class.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		if ( ! class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
			return;
		}

		\add_action( 'after_setup_theme', [ $this, 'boot' ] );
	}

	/**
	 * Method to boot Carbon Fields.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function boot() {
		\Carbon_Fields\Carbon_Fields::boot();
	}
}
