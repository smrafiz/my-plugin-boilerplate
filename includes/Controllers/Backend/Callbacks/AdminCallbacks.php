<?php
/**
 * Backend Class: Admin Callbacks
 *
 * The list of all callbacks from admin pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Backend\Callbacks;

use Prefix\MyPluginBoilerplate\Common\{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Backend Class: Admin Callbacks
 *
 * @since 1.0.0
 */
class AdminCallbacks extends Base {

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
	}

	/**
	 * Callback: Section.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function adminSectionManager() {
		echo esc_html__( 'This is the sample checkbox fields to showcase the plugin settings page.', 'my-plugin-text-domain' );
	}

	/**
	 * Callback: Dashboard Page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function adminDashboard() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'My Plugin Boilerplate', 'my-plugin-text-domain' ); ?></h1>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'my_plugin_boilerplate_settings' );
					do_settings_sections( 'my_plugin_boilerplate' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Callback: SubPage.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function subPage() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Sub Page', 'my-plugin-text-domain' ); ?></h1>
			<?php settings_errors(); ?>

			<h2><?php echo esc_html__( 'My Plugin Boilerplate Sub Page', 'my-plugin-text-domain' ); ?></h2>
		</div>
		<?php
	}

	/**
	 * Callback: SubPage 2.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function subPage2() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Sub Page 2', 'my-plugin-text-domain' ); ?></h1>
			<?php settings_errors(); ?>

			<h2><?php echo esc_html__( 'My Plugin Boilerplate Sub Page', 'my-plugin-text-domain' ); ?></h2>
		</div>
		<?php
	}
}
