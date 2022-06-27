<?php
/**
 * Backend Class: Callbacks
 *
 * The list of all callback functions.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Functions;

/**
 * Backend Class: Callbacks
 *
 * @since 1.0.0
 */
class Callbacks {

	/**
	 * Callback: Admin Section.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function adminSectionManager() {
		echo esc_html__( 'This is the sample checkbox fields to showcase the plugin settings page.', 'my-plugin-text-domain' );
	}

	/**
	 * Callback: Admin Dashboard Page
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function adminDashboard() {
		return Helpers::renderView( 'admin-pages.dashboard' );
	}

	/**
	 * Callback: Admin SubPage.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function subPage() {
		return Helpers::renderView( 'admin-pages.subpage' );
	}

	/**
	 * Callback: Admin SubPage 2.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function subPage2() {
		return Helpers::renderView( 'admin-pages.subpage-2' );
	}
}
