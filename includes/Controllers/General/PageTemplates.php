<?php
/**
 * General Class: PageTemplates
 *
 * This class loads the required public page templates.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\General;

use Prefix\MyPluginBoilerplate\Common\Models\PageTemplates as Templates;
use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Class: PageTemplates
 *
 * @package ThePluginName\App\Backend
 * @since 1.0.0
 */
class PageTemplates extends Base {

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
			'init',
			function () {
				new Templates(
					[
						'test-page-template.php' => esc_html__( 'Test Page Template', 'my-plugin-text-domain' ),
					]
				);
			}
		);
	}
}
