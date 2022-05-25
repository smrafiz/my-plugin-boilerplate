<?php
/**
 * Backend Class: AdminEnqueue
 *
 * This class enqueues required styles & scripts in the admin pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Backend;

use Prefix\MyPluginBoilerplate\Common\
{
	Traits\Singleton,
	Abstracts\Enqueue
};

/**
 * Class: AdminEnqueue
 *
 * @package ThePluginName\App\Backend
 * @since 1.0.0
 */
class AdminEnqueue extends Enqueue {

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
	 * This backend class is only being instantiated in the backend
	 * as requested in the Bootstrap class.
	 *
	 * @see Requester::isAdminBackend()
	 * @see Bootstrap::registerServices
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		$this->assets();

		if ( empty( $this->assets() ) ) {
			return;
		}

		\add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Method to accumulate styles list.
	 *
	 * @return AdminEnqueue
	 * @since 1.0.0
	 */
	protected function getStyles() {
		$styles = [];

		$styles[] = [
			'handle' => 'thickbox',
		];

		$styles[] = [
			'handle'    => 'my-plugin-boilerplate-admin-styles',
			'asset_uri' => $this->plugin->assetsUri() . '/css/admin' . $this->plugin->suffix . '.css',
			'version'   => $this->plugin->version(),
		];

		$this->enqueues['style'] = \apply_filters( 'my_plugin_boilerplate_registered_admin_styles', $styles, 10, 1 );

		return $this;
	}

	/**
	 * Method to accumulate scripts list.
	 *
	 * @return AdminEnqueue
	 * @since 1.0.0
	 */
	protected function getScripts() {
		$scripts = [];

		$scripts[] = [
			'handle' => 'media-upload',
		];

		$scripts[] = [
			'handle' => 'thickbox',
		];

		$scripts[] = [
			'handle'     => 'my-plugin-boilerplate-admin-script',
			'asset_uri'  => $this->plugin->assetsUri() . '/js/admin/admin' . $this->plugin->suffix . '.js',
			'dependency' => [ 'jquery' ],
			'in_footer'  => true,
			'version'    => $this->plugin->version(),
		];

		$this->enqueues['script'] = \apply_filters( 'my_plugin_boilerplate_registered_admin_scripts', $scripts, 10, 1 );

		return $this;
	}

	/**
	 * Method to enqueue scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue() {
		$this
			->registerScripts()
			->enqueueScripts()
			->localize( $this->localizeData() );
	}

	/**
	 * Localized data.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function localizeData() {
		return [
			'handle' => 'my-plugin-boilerplate-admin-script',
			'object' => 'my_plugin_boilerplate_admin_object',
			'data'   => [
				'ajaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			],
		];
	}
}
