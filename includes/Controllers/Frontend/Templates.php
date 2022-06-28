<?php
/**
 * Frontend Class: Templates
 *
 * This class loads the required public templates.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Frontend;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Class: Templates
 *
 * @package ThePluginName\App\Backend
 * @since 1.0.0
 */
class Templates extends Base {

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
	 * This frontend class is only being instantiated in the frontend
	 * as requested in the Bootstrap class.
	 *
	 * @see Requester::isFrontend()
	 * @see Bootstrap::registerServices
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		add_action(
			'wp_footer',
			function () {
				my_plugin_boilerplate()->templates()->get(
					'test-template',
					null,
					[ 'data' => [ 'text' => 'with arguments' ] ]
				);
			}
		);
	}
}
