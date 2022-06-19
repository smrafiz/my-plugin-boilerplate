<?php
/**
 * Backend Class: Pages
 *
 * This class creates the necessary admin pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Backend;

use Prefix\MyPluginBoilerplate\Common\Models\Settings;
use Prefix\MyPluginBoilerplate\Controllers\Backend\Callbacks\AdminCallbacks;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Backend Class: Pages
 *
 * @since 1.0.0
 */
class Pages extends Base {

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
		new Settings(
			$this->setPages(),
			$this->setSubPages(),
			[
				'settings' => $this->setSettings(),
				'sections' => $this->setSections(),
				'fields'   => $this->setFields(),
			]
		);
	}

	/**
	 * Method to accumulate admin pages list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function setPages() {
		return [
			[
				'page_title'     => __( 'My Plugin Boilerplate', 'my-plugin-text-domain' ),
				'menu_title'     => __( 'My Plugin', 'my-plugin-text-domain' ),
				'capability'     => 'manage_options',
				'menu_slug'      => 'my_plugin_boilerplate',
				'callback'       => [ AdminCallbacks::class, 'adminDashboard' ],
				'icon_url'       => 'dashicons-admin-settings',
				'position'       => 110,
				'top_menu_title' => __( 'Dashboard', 'my-plugin-text-domain' ),
			],
		];
	}

	/**
	 * Method to accumulate admin pages list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function setSubPages() {
		return [
			[
				'parent_slug' => 'my_plugin_boilerplate',
				'page_title'  => __( 'Sub Page', 'my-plugin-text-domain' ),
				'menu_title'  => __( 'Sub Page', 'my-plugin-text-domain' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'my_plugin_boilerplate_sub_page',
				'callback'    => [ AdminCallbacks::class, 'subPage' ],
			],
			[
				'parent_slug' => 'my_plugin_boilerplate',
				'page_title'  => __( 'Sub Page 2', 'my-plugin-text-domain' ),
				'menu_title'  => __( 'Sub Page 2', 'my-plugin-text-domain' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'my_plugin_boilerplate_sub_page_2',
				'callback'    => [ AdminCallbacks::class, 'subPage2' ],
			],
		];
	}

	/**
	 * Method to accumulate settings list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function setSettings() {
		return [
			[
				'option_group' => 'my_plugin_boilerplate_settings',
				'option_name'  => 'my_plugin_boilerplate',
				'callback'     => '',
			],
		];
	}

	/**
	 * Method to accumulate sections list
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function setSections() {
		return [
			[
				'id'       => 'my_plugin_boilerplate_admin_index',
				'title'    => __( 'Settings Manager', 'my-plugin-text-domain' ),
				'callback' => [ AdminCallbacks::class, 'adminSectionManager' ],
				'page'     => 'my_plugin_boilerplate',
			],
		];
	}

	/**
	 * Method to accumulate fields list
	 *
	 * @return return
	 * @since 1.0.0
	 */
	public function setFields() {
		$fields        = [];
		$sample_fields = [
			'sample_field_1' => __( 'Sample Checkbox 1', 'my-plugin-text-domain' ),
			'sample_field_2' => __( 'Sample Checkbox 2', 'my-plugin-text-domain' ),
			'sample_field_3' => __( 'Sample Checkbox 3', 'my-plugin-text-domain' ),
		];

		foreach ( $sample_fields as $key => $value ) {
			$fields[] = [
				'id'       => $key,
				'title'    => $value,
				'callback' => [ $this, 'checkboxField' ],
				'page'     => 'my_plugin_boilerplate',
				'section'  => 'my_plugin_boilerplate_admin_index',
				'args'     => [
					'option_name' => 'my_plugin_boilerplate',
					'label_for'   => $key,
					'class'       => 'ui-toggle',
				],
			];
		}

		return $fields;
	}

	/**
	 * Checkbox field.
	 *
	 * @param array $args Settings args.
	 * @return void
	 * @since 1.0.0
	 */
	public function checkboxField( $args ) {
		$name        = $args['label_for'];
		$classes     = $args['class'];
		$option_name = $args['option_name'];
		$checkbox    = get_option( $option_name );
		$checked     = isset( $checkbox[ $name ] ) ? ( $checkbox[ $name ] ? true : false ) : false;

		echo '<div class="' . esc_attr( $classes ) . '"><input type="checkbox" id="' . esc_attr( $name ) . '" name="' . esc_attr( $option_name ) . '[' . esc_attr( $name ) . ']" value="1" class="" ' . ( $checked ? 'checked' : '' ) . '><label for="' . esc_attr( $name ) . '"><div></div></label></div>';
	}
}
