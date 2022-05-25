<?php
/**
 * Cron Class: Schedule
 *
 * Example cron schedule.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Cron;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};


/**
 * Cron Class: Schedule
 *
 * @since 1.0.0
 */
class Schedule extends Base {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Registers the class.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		/**
		 * This class is only being instantiated if DOING_CRON is defined
		 * in the requester as requested in the Bootstrap class.
		 *
		 * @see Requester::isCron()
		 * @see Bootstrap::registerServices()
		 */

		\add_action( 'wp', [ $this, 'activationDeactivationExample' ] );
		\add_action( 'plugin_cronjobs', [ $this, 'cronjobRepeatingFunctionExample' ] );
	}

	/**
	 * Activates a scheduled event (if it does not exist already)
	 *
	 * @since 1.0.0
	 */
	public function activationDeactivationExample() {
		if ( ! wp_next_scheduled( 'plugin_cronjobs' ) ) {
			wp_schedule_event( time(), 'daily', 'plugin_cronjobs' );
		} else {
			$timestamp = wp_next_scheduled( 'plugin_cronjobs' );
			wp_unschedule_event( $timestamp, 'plugin_cronjobs' );
		}
	}

	/**
	 * The function that gets called with the scheduled event
	 *
	 * @since 1.0.0
	 */
	public function cronjobRepeatingFunctionExample() {
		// do here what needs to be done automatically as per schedule.
	}
}
