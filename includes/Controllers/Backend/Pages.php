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

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Settings,
	Traits\Singleton
};

/**
 * Backend Class: Pages
 *
 * @since 1.0.0
 */
class Pages extends Settings {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Method to accumulate admin pages list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function setPages() {
		return [
			[
				'page_title'     => 'Alecaddd Plugin',
				'menu_title'     => 'Alecaddd',
				'capability'     => 'manage_options',
				'menu_slug'      => 'alecaddd_plugin',
				'callback'       => [ $this, 'adminDashboard' ],
				'icon_url'       => 'dashicons-store',
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
				'parent_slug' => 'alecaddd_plugin',
				'page_title'  => 'Sub Page',
				'menu_title'  => 'Sub Page',
				'capability'  => 'manage_options',
				'menu_slug'   => 'cx_sub_page',
				'callback'    => [ $this, 'subPage' ],
			],
			[
				'parent_slug' => 'alecaddd_plugin_2',
				'page_title'  => 'Sub Page2',
				'menu_title'  => 'Sub Page2',
				'capability'  => 'manage_options',
				'menu_slug'   => 'cx_sub_page_2',
				'callback'    => [ $this, 'subPage' ],
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
				'option_group' => 'alecaddd_plugin_settings',
				'option_name'  => 'alecaddd_plugin',
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
				'id'       => 'alecaddd_admin_index',
				'title'    => 'Settings Manager',
				'callback' => [ $this, 'adminSectionManager' ],
				'page'     => 'alecaddd_plugin',
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
		$fields   = [];
		$managers = [
			'cpt_manager'         => 'Activate CPT Manager',
			'gallery_manager'     => 'Activate Gallery Manager',
			'testimonial_manager' => 'Activate Testimonial Manager',
		];

		foreach ( $managers as $key => $value ) {
			$fields[] = [
				'id'       => $key,
				'title'    => $value,
				'callback' => [ $this, 'checkboxField' ],
				'page'     => 'alecaddd_plugin',
				'section'  => 'alecaddd_admin_index',
				'args'     => [
					'option_name' => 'alecaddd_plugin',
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

	/**
	 * Callback: Section.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function adminSectionManager() {
		echo esc_html__( 'Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.', 'my-plugin-text-domain' );
	}

	/**
	 * Callback: Dashboard Page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function adminDashboard() {
		?>
		<div class="wrap">
			<h1>Alecaddd Plugin</h1>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'alecaddd_plugin_settings' );
					do_settings_sections( 'alecaddd_plugin' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Callback: Subpage.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function subPage() {
		?>
		<div class="wrap">
			<h1>Sub Page</h1>
			<?php settings_errors(); ?>

			<h2>This is a sub page</h2>
		</div>
		<?php
	}
}
