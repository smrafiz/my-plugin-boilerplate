<?php
/**
 * General Class: Carbon Fields.
 *
 * This class initializes Carbon Fields.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

namespace Prefix\MyPluginBoilerplate\Integrations\Api;

use Carbon_Fields\{
	Field,
	Container,
	Carbon_Fields
};
use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * General Class: Carbon Fields.
 *
 * @since 1.0.0
 */
class CarbonFields extends Base {

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
	 * This general class is always being instantiated as requested in the
	 * Bootstrap class
	 *
	 * @see Bootstrap::registerServices
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		if ( ! class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
			return;
		}

		// Booting up Carbon Fields.
		\add_action( 'after_setup_theme', [ $this, 'boot' ] );

		// Creating pages with Carbon Fields.
		\add_action( 'carbon_fields_register_fields', [ $this, 'createPages' ] );
	}

	/**
	 * Method to boot Carbon Fields.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function boot() {
		Carbon_Fields::boot();
	}

	/**
	 * Creating Carbon Fields Pages.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function createPages() {
		$page = Container::make(
			'theme_options',
			__( 'CF Page', 'my-plugin-text-domain' )
		);

		$page
			->set_page_parent( 'my_plugin_boilerplate' )
			->set_page_file( 'cf-page' )
			->set_classes( 'cf-admin-page' )
			->add_fields( $this->customFields() );

		$postMeta = Container::make(
			'post_meta',
			__( 'CF Custom Fields', 'my-plugin-text-domain' )
		);

		$postMeta
			->where( 'post_type', '=', 'test_gallery' )
			->add_fields(
				[
					Field::make(
						'text',
						'my_plugin_boilerplate_cf_text',
						__( 'CF Text Field', 'my-plugin-text-domain' )
					),
					Field::make(
						'rich_text',
						'my_plugin_boilerplate_cf_rich_text',
						__( 'CF Rich Text Field', 'my-plugin-text-domain' )
					),
					Field::make(
						'media_gallery',
						'my_plugin_boilerplate_cf_gallery',
						__( 'CF Gallery Field', 'my-plugin-text-domain' )
					),
				]
			);
	}

	/**
	 * Creating Custom Fields with Carbon Fields.
	 *
	 * These settings needs to accessible in both frontend and backend.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function customFields() {
		$fields = [];

		$fields[] = Field::make(
			'separator',
			'my_plugin_boilerplate_cf_posts',
			esc_html__( 'Add Posts', 'my-plugin-text-domain' )
		);

		$fields[] = Field::make(
			'complex',
			'my_plugin_boilerplate_cf_post_list',
			''
		)
			->set_collapsed( true )
			->setup_labels(
				[
					'plural_name'   => esc_html__( 'New', 'my-plugin-text-domain' ),
					'singular_name' => esc_html__( 'New', 'my-plugin-text-domain' ),
				]
			)
			->add_fields(
				[
					Field::make(
						'text',
						'my_plugin_boilerplate_cf_post_title',
						esc_html__( 'Post Title', 'my-plugin-text-domain' )
					)
					->set_help_text( esc_html__( 'Please enter the post title.', 'my-plugin-text-domain' ) ),

					Field::make(
						'rich_text',
						'my_plugin_boilerplate_cf_post_content',
						esc_html__( 'Post Content', 'my-plugin-text-domain' )
					)
					->set_help_text( esc_html__( 'Please enter the post content.', 'my-plugin-text-domain' ) ),
				]
			)
			->set_header_template(
				'
				<% if (my_plugin_boilerplate_cf_post_title) { %>
					Post Title: <%- my_plugin_boilerplate_cf_post_title %>
				<% } %>
			'
			);

		return $fields;
	}
}
