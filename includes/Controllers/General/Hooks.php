<?php
/**
 * General Class: Hooks.
 *
 * This class initializes all the Action & Filter Hooks.
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

use Prefix\MyPluginBoilerplate\Common\Functions\
{
	Actions,
	Filters
};

/**
 * General Class: Actions Hooks.
 *
 * @since 1.0.0
 */
class Hooks extends Base {

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
	 * This general class is always being instantiated as requested in the
	 * Bootstrap class
	 *
	 * @see Bootstrap::registerServices
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		$this
			->actions()
			->filters();
	}

	/**
	 * List of action hooks
	 *
	 * @return Hooks
	 */
	public function actions() {
		\add_action( 'init', [ Actions::class, 'testAction' ] );

		return $this;
	}

	/**
	 * List of filter hooks
	 *
	 * @return Hooks
	 */
	public function filters() {
		\add_filter( 'my_plugin_boilerplate_post_type_title', [ Filters::class, 'testFilter' ], 99 );

		return $this;
	}
}
