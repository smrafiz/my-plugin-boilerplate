<?php
/**
 * Config Class: Requirements.
 *
 * Check if any requirements are needed to run this plugin.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since 1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Config;

use Prefix\MyPluginBoilerplate\Controllers\Backend\Notice;
use Prefix\MyPluginBoilerplate\Common\{
	Utils\Errors,
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Class: Requirements.
 *
 * @since 1.0.0
 */
final class Requirements extends Base {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Specifications for the requirements.
	 *
	 * @return array Used to specify the requirements.
	 * @since 1.0.0
	 */
	public function specifications() {
		return apply_filters(
			'my_plugin_boilerplate_plugin_requirements',
			[
				'php' => $this->plugin->requiredPhp(),
				'wp'  => $this->plugin->requiredWp(),
			]
		);
	}

	/**
	 * Plugin requirements checker
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function check() {
		foreach ( $this->versionCompare() as $compatCheck ) {
			if ( version_compare(
				$compatCheck['compare'],
				$compatCheck['current'],
				'>='
			) ) {
				$error = Errors::errorMessage(
					$compatCheck['message'],
					$compatCheck['title'],
					'',
					plugin_basename( __FILE__ )
				);

				// Through error & kill plugin.
				$this->throughError( $error );
			}
		};
	}

	/**
	 * Compares PHP & WP versions and kills plugin if it's not compatible
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function versionCompare() {
		return [
			// PHP version check.
			[
				'current' => phpversion(),
				'compare' => $this->plugin->requiredPhp(),
				'title'   => __( 'Invalid PHP version', 'my-plugin-text-domain' ),
				'message' => sprintf(
					/* translators: %1$1s: required php version, %2$2s: current php version */
					__( 'You must be using PHP %1$1s or greater. You are currently using PHP %2$2s.', 'my-plugin-text-domain' ),
					$this->plugin->requiredPhp(),
					phpversion()
				),
			],
			// WP version check.
			[
				'current' => get_bloginfo( 'version' ),
				'compare' => $this->plugin->requiredWp(),
				'title'   => __( 'Invalid WordPress version', 'my-plugin-text-domain' ),
				'message' => sprintf(
					/* translators: %1$1s: required wordpress version, %2$2s: current wordpress version */
					__( 'You must be using WordPress %1$1s or greater. You are currently using WordPress %2$2s.', 'my-plugin-text-domain' ),
					$this->plugin->requiredWp(),
					get_bloginfo( 'version' )
				),
			],
		];
	}

	/**
	 * Gives an admin notice and deactivates plugin.
	 *
	 * @param string $error Error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function throughError( $error ) {

		// Gives a error notice.
		Notice::instance()->trigger( $error, 'error' );

		// Kill plugin.
		Errors::pluginDie();
	}
}
