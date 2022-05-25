<?php
/**
 * Backend Class: Notice.
 *
 * Gives a notice if plugin reqiurements are not met.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Backend;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Class: Notice.
 *
 * @since 1.0.0
 */
class Notice extends Base {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Notice message.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $message;

	/**
	 * Type of notice.
	 * Possible values are error, warning, success, info.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $type;

	/**
	 * Close button.
	 * If true, notice will include a close button.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private $close = false;

	/**
	 * Initialize the class.
	 *
	 * @param string  $message Notice message.
	 * @param string  $type Type of notice.
	 * @param boolean $close Close button.
	 * @since 1.0.0
	 */
	public function trigger( $message, $type, $close = false ) {
		$this->message = $message;
		$this->type    = $type;
		$this->close   = $close;

		\add_action( 'admin_notices', [ $this, 'notice' ] );
	}

	/**
	 * Admin notice.
	 *
	 * @since 1.0.0
	 */
	public function notice() {
		$hasClose = $this->close ? ' is-dismissible' : '';

		echo \wp_kses_post(
			sprintf(
				'<div class="notice notice-' . $this->type . $hasClose . '">%s</div>',
				$this->message
			)
		);
	}
}
