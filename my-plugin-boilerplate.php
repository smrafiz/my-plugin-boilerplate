<?php
/**
 * Plugin Name: My Plugin Boilerplate
 * Plugin URI: https://github.com/smrafiz/my-plugin-boilerplate/
 * Description: A modern WordPress plugin boilerplate.
 * Version: 1.0.0
 * Author: S.M. Rafiz
 * Author URI: https://github.com/smrafiz/
 * License: GPLv2 or later
 * Text Domain: my-plugin-text-domain
 * Domain Path: /languages
 * Namespace: Prefix\MyPluginBoilerplate
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * TODO:
 * 1. Sample Post Meta
 * 2. Sample CF Post Meta
 * 3. Create admin views
 * 4. Page template support
 * 5. Ajax Controller
 * 6. Shortcode support
 * 7. Blocks support
 * 8. Elementor widget support
 */

declare( strict_types = 1 );

// If this file is called directly, abort!!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the default root file of the plugin.
 *
 * @since 1.0.0
 */
define( 'MY_PLUGIN_BOILERPLATE_PLUGIN_ROOT_FILE', __FILE__ );

/**
 * Load PSR4 autoloader.
 *
 * @since 1.0.0
 */
$my_plugin_boilerplate_autoloader = require plugin_dir_path( MY_PLUGIN_BOILERPLATE_PLUGIN_ROOT_FILE ) . 'vendor/autoload.php';

/**
 * Setup hooks (activation, deactivation, uninstall)
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, [ 'Prefix\MyPluginBoilerplate\Config\Setup', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'Prefix\MyPluginBoilerplate\Config\Setup', 'deactivation' ] );
register_uninstall_hook( __FILE__, [ 'Prefix\MyPluginBoilerplate\Config\Setup', 'uninstall' ] );

/**
 * Bootstrap the plugin.
 *
 * @param object $my_plugin_boilerplate_autoloader Autoloader Object.
 * @since 1.0.0
 */
if ( ! class_exists( 'Prefix\MyPluginBoilerplate\\Bootstrap' ) ) {
	wp_die( esc_html__( 'My Plugin Boilerplate is unable to find the Bootstrap class.', 'my-plugin-text-domain' ) );
}

add_action(
	'plugins_loaded',
	static function () use ( $my_plugin_boilerplate_autoloader ) {
		$app = \Prefix\MyPluginBoilerplate\Bootstrap::instance();
		$app->registerServices( $my_plugin_boilerplate_autoloader );
	}
);
