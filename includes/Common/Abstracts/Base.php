<?php
/**
 * Abstract Class: Base.
 *
 * The Base class which can be extended by other
 * classes to load in default methods.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Abstracts;

use Prefix\MyPluginBoilerplate\Config\Plugin;

/**
 * Abstract Class: Base.
 *
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * Data from the plugin config class.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $plugin = [];

	/**
	 * Base Constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin = Plugin::instance();
	}
}
