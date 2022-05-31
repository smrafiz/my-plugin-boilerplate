<?php
/**
 * Frontend Class: PublicEnqueue
 *
 * This class enqueues required styles & scripts in the frontend pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Frontend;

use Prefix\MyPluginBoilerplate\Common\
{
	Traits\Singleton,
	Abstracts\Enqueue
};

/**
 * Class: PublicEnqueue
 *
 * @package ThePluginName\App\Backend
 * @since 1.0.0
 */
class PublicEnqueue extends Enqueue {

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
		$this->assets();

		if ( empty( $this->assets() ) ) {
			return;
		}

		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Method to accumulate styles list.
	 *
	 * @return PublicEnqueue
	 * @since 1.0.0
	 */
	protected function getStyles() {
		$styles = [];

		$styles[] = [
			'handle'    => 'my-plugin-boilerplate-frontend-styles',
			'asset_uri' => $this->plugin->assetsUri() . '/css/frontend' . $this->plugin->suffix . '.css',
			'version'   => $this->plugin->version(),
		];

		$this->enqueues['style'] = \apply_filters( 'my_plugin_boilerplate_registered_frontend_styles', $styles, 10, 1 );

		return $this;
	}

	/**
	 * Method to accumulate scripts list.
	 *
	 * @return PublicEnqueue
	 * @since 1.0.0
	 */
	protected function getScripts() {
		$scripts = [];

		$scripts[] = [
			'handle'     => 'my-plugin-boilerplate-frontend-script',
			'asset_uri'  => $this->plugin->assetsUri() . '/js/frontend' . $this->plugin->suffix . '.js',
			'dependency' => [ 'jquery' ],
			'in_footer'  => true,
			'version'    => $this->plugin->version(),
		];

		$this->enqueues['script'] = \apply_filters( 'my_plugin_boilerplate_registered_frontend_scripts', $scripts, 10, 1 );

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

		// Send variables to JS.
		global $wp_query;

		return [
			'handle' => 'my-plugin-boilerplate-frontend-script',
			'object' => 'my_plugin_boilerplate_frontend_object',
			'data'   => [
				'ajaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'wpQueryVars' => $wp_query->query_vars,
			],
		];
	}
}
