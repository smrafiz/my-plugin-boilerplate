<?php
/**
 * Class: Bootstrap.
 *
 * The main handler class responsible for initializing My Plugin Boilerplate.
 * This class registers all the core modules required to run the plugin.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Requester,
	Traits\Singleton
};

use Prefix\MyPluginBoilerplate\Config\
{
	I18n,
	Classes,
	Requirements
};

/**
 * Plugin Initialization Class.
 *
 * @since 1.0.0
 */
final class Bootstrap extends Base {

	/**
	 * Traits.
	 *
	 * @see Singleton
	 * @see Requester
	 * @since 1.0.0
	 */
	use Singleton, Requester;

	/**
	 * List of services to register.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $services = [];

	/**
	 * Composer autoload file list.
	 *
	 * @var Composer\Autoload\ClassLoader
	 * @since 1.0.0
	 */
	public $composer;

	/**
	 * Requirements class object.
	 *
	 * @var Requirements
	 * @since 1.0.0
	 */
	protected $requirements;

	/**
	 * I18n class object.
	 *
	 * @var I18n
	 * @since 1.0.0
	 */
	protected $i18n;

	/**
	 * Register plugin services.
	 *
	 * @param \Composer\Autoload\ClassLoader $composer Composer autoload output.
	 * @return void
	 * @since 1.0.0
	 */
	public function registerServices( $composer ) {
		\do_action( 'my_plugin_boilerplate_plugin_loaded' );

		// Check plugin requirements.
		$this->checkRequirements();

		// Define the locale.
		$this->setLocale();

		// class loader from Composer.
		$this->getClassLoader( $composer );

		// Load services.
		$this->loadServices( Classes::register() );
	}

	/**
	 * Check plugin requirements.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function checkRequirements() {
		$this->requirements = Requirements::instance();
		$this->requirements->check();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setLocale() {
		$this->i18n = I18n::instance();
		$this->i18n->load();
	}

	/**
	 * Get the class loader from Composer
	 *
	 * @param object $composer Autoloader object.
	 * @since 1.0.0
	 */
	public function getClassLoader( $composer ) {
		$this->composer = $composer;
	}

	/**
	 * Initialize the requested services.
	 *
	 * @param array $services The loaded services.
	 * @since 1.0.0
	 */
	public function loadServices( $services ) {
		foreach ( $services as $service ) {
			if ( isset( $service['on_request'] ) && is_array( $service['on_request'] )
			) {
				foreach ( $service['on_request'] as $on_request ) {
					if ( ! $this->request( $on_request ) ) {
						continue;
					}
				}
			} elseif ( isset( $service['on_request'] ) && ! $this->request( $service['on_request'] )
			) {
				continue;
			}

			// Get the services.
			$this->getServices( $service['register'] );
		}

		// Init the services.
		$this->initServices();
	}

	/**
	 * Get classes based on the directory automatically
	 * using the Composer autoload.
	 *
	 * This method checks for optimized class autoloads to reduce
	 * server load time. If the newly added class is not found,
	 * run 'composer dump-autoload -o' command.
	 *
	 * @param string $service Class name to find.
	 * @return array Return the classes.
	 * @since 1.0.0
	 */
	public function getServices( string $service ) {
		$service = $this->plugin->namespace() . '\\' . $service;

		if ( is_object( $this->composer ) === false ) {
			return $this->services;
		}

		$classmap = $this->composer->getClassMap();
		$classes  = array_keys( $classmap );

		foreach ( $classes as $class ) {
			if ( 0 !== strncmp( (string) $class, $service, strlen( $service ) ) ) {
				continue;
			}

			$this->services[] = $class;
		}

		return $this->services;
	}

	/**
	 * Initialize the services.
	 *
	 * @since 1.0.0
	 */
	public function initServices() {
		$this->services = \apply_filters( 'my_plugin_boilerplate_initialized_classes', $this->services );

		foreach ( $this->services as $service ) {
			$class = $service::instance();

			if ( method_exists( $class, 'register' ) ) {
				$class->register();
			}
		}
	}
}
