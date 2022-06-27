<?php
/**
 * Class: Plugin.
 *
 * Plugin data which are used through the plugin, most of them are defined
 * by the root file meta data. The data is being inserted in each class
 * that extends the Base abstract class.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Config;

use Prefix\MyPluginBoilerplate\Common\Traits\Singleton;

/**
 * Class: Plugin.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Get the plugin meta data.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function data() {
		return array_merge(
			apply_filters(
				'my_plugin_boilerplate_plugin_meta_data',
				$this->getPluginMetaData()
			),
			$this->getOwnPluginData()
		);
	}

	/**
	 * Get own plugin data.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getOwnPluginData() {
		return [
			'settings'            => get_option( 'my-plugin-boilerplate-settings' ),
			'plugin_path'         => \untrailingslashit( \plugin_dir_path( MY_PLUGIN_BOILERPLATE_PLUGIN_ROOT_FILE ) ),
			'plugin_uri'          => \untrailingslashit( \plugin_dir_url( MY_PLUGIN_BOILERPLATE_PLUGIN_ROOT_FILE ) ),
			'views_folder'        => 'views',
			'template_folder'     => 'templates',
			'ext_template_folder' => 'my-plugin-boilerplate-templates',
			'assets_folder'       => 'assets',
			'required_php'        => '7.1',
			'required_wp'         => '5.0',
			/**
			 * Add extra data here
			 */
		];
	}

	/**
	 * Get the plugin meta data.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getPluginMetaData() {
		return get_file_data(
			MY_PLUGIN_BOILERPLATE_PLUGIN_ROOT_FILE,
			[
				'name'        => 'Plugin Name',
				'version'     => 'Version',
				'text-domain' => 'Text Domain',
				'domain-path' => 'Domain Path',
				'namespace'   => 'Namespace',
			],
			'plugin'
		);
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function pluginPath() {
		return $this->data()['plugin_path'];
	}

	/**
	 * Get the plugin URL.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function pluginUri() {
		return $this->data()['plugin_uri'];
	}

	/**
	 * Get the plugin internal template path.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function templatePath() {
		return $this->data()['plugin_path'] . '/' . $this->data()['template_folder'];
	}

	/**
	 * Get the plugin internal views folder name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function viewsFolder() {
		return $this->data()['views_folder'];
	}

	/**
	 * Get the plugin internal template folder name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function templateFolder() {
		return $this->data()['template_folder'];
	}

	/**
	 * Get the plugin external template path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function extTemplateFolder() {
		return $this->data()['ext_template_folder'];
	}

	/**
	 * Get the plugin assets URL.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function assetsUri() {
		return $this->data()['plugin_uri'] . '/' . $this->data()['assets_folder'];
	}

	/**
	 * Get the plugin settings.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function settings() {
		return $this->data()['settings'];
	}

	/**
	 * Get the plugin version number.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function version() {
		return $this->data()['version'];
	}

	/**
	 * Get the plugin name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function name() {
		return $this->data()['name'];
	}

	/**
	 * Get the plugin text domain.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function textDomain() {
		return $this->data()['text-domain'];
	}

	/**
	 * Get the plugin domain path.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function domainPath() {
		return $this->data()['domain-path'];
	}

	/**
	 * Get the plugin required php version.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function requiredPhp() {
		return $this->data()['required_php'];
	}

	/**
	 * Get the plugin required wp version.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function requiredWp() {
		return $this->data()['required_wp'];
	}

	/**
	 * Get the plugin namespace.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function namespace() {
		return $this->data()['namespace'];
	}
}
