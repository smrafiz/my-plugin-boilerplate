<?php
/**
 * Abstract Class: Enqueue.
 *
 * The Enqueue class which can be extended by other
 * classes to registers all scripts & styles.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Abstracts;

use Prefix\MyPluginBoilerplate\Common\Abstracts\Base;

/**
 * Abstract Class: Enqueue.
 *
 * @since 1.0.0
 */
abstract class Enqueue extends Base {

	/**
	 * Holds script file name suffix.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $suffix = null;

	/**
	 * Accumulates scripts.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $enqueues = [];

	/**
	 * Class Constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->plugin->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Method to register scripts.
	 *
	 * @return void|class
	 * @since 1.0.0
	 */
	protected function registerScripts() {

		if ( empty( $this->enqueues ) ) {
			return;
		}

		$registerFunction = '';

		foreach ( $this->enqueues as $type => $enqueue ) {
			$registerFunction = '\wp_register_' . $type;

			foreach ( $enqueue as $key ) {
				if ( isset( $key['in_footer'] ) ) {
					$loadInFooter = (bool) $key['in_footer'];
				} else {
					$loadInFooter = (bool) true;
				}

				$registerFunction(
					isset( $key['handle'] ) ? $key['handle'] : '',
					isset( $key['asset_uri'] ) ? $key['asset_uri'] : '',
					isset( $key['dependency'] ) ? $key['dependency'] : [],
					isset( $key['version'] ) ? $key['version'] : null,
					( 'style' === $type ) ? 'all' : $loadInFooter
				);
			}
		}

		return $this;
	}

	/**
	 * Method to enqueue scripts.
	 *
	 * @return void|class
	 * @since 1.0.0
	 */
	protected function enqueueScripts() {

		if ( empty( $this->enqueues ) ) {
			return;
		}

		$enqueueFunction = '';

		foreach ( $this->enqueues as $type => $enqueue ) {
			$enqueueFunction = '\wp_enqueue_' . $type;

			foreach ( $enqueue as $key ) {
				$enqueueFunction( $key['handle'] );
			}
		}

		return $this;
	}

	/**
	 * Method to localize script.
	 *
	 * @param array $args Localize args.
	 * @return void
	 * @since 1.0.0
	 */
	protected function localize( array $args ) {
		\wp_localize_script(
			$args['handle'],
			$args['object'],
			$args['data']
		);
	}

	/**
	 * Method to accumulate scripts & styles.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected function assets() {
		$this->getStyles()->getScripts();

		return $this->enqueues;
	}

	/**
	 * Method to accumulate styles list.
	 *
	 * @return Enqueue
	 * @since 1.0.0
	 */
	abstract protected function getStyles();

	/**
	 * Method to accumulate scritps list.
	 *
	 * @return Enqueue
	 * @since 1.0.0
	 */
	abstract protected function getScripts();

	/**
	 * Method to enqueue scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	abstract public function enqueue();
}
