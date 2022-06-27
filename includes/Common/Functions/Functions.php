<?php
/**
 * Functions Class: Functions
 *
 * Main function class for external uses
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Functions;

use Prefix\MyPluginBoilerplate\Common\Abstracts\Base;
use Prefix\MyPluginBoilerplate\Common\Models\Templates;

/**
 * Functions Class: Functions
 *
 * @since 1.0.0
 */
class Functions extends Base {

	/**
	 * Get plugin data by using the_plugin_boilerplate()->getData()
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getData() {
		return $this->plugin->data();
	}

	/**
	 * Get plugin data by using the_plugin_boilerplate()->templatesPath()
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function templatesPath() {
		return $this->plugin->templatePath();
	}

	/**
	 * Get the template class using the_plugin_boilerplate()->templates()
	 *
	 * @return Templates
	 * @since 1.0.0
	 */
	public function templates() {
		return new Templates();
	}
}
