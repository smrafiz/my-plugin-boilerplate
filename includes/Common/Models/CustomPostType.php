<?php
/**
 * Model Class: Custom Post Type
 *
 * This class is responsible for creating a Custom Post Type.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Models;

use Prefix\MyPluginBoilerplate\Common\Functions\Helpers;

/**
 * Class: Post Type
 *
 * @since  1.0.0
 */
class CustomPostType {

	/**
	 * Post type name.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	public $postTypeName;

	/**
	 * Post type slug.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	public $postTypeSlug;

	/**
	 * Post type args.
	 *
	 * @var array
	 * @since  1.0.0
	 */
	public $postTypeArgs;

	/**
	 * Post type labels.
	 *
	 * @var array
	 * @since  1.0.0
	 */
	public $postTypeLabels;

	/**
	 * Class Constructor.
	 *
	 * Registers a custom post type.
	 *
	 * @param string $name Post type name.
	 * @param string $slug Post type slug.
	 * @param array  $labels Post type labels.
	 * @param array  $args Post type args.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function __construct( $name, $slug, $labels = [], $args = [] ) {
		$this->postTypeName   = Helpers::uglify( $name );
		$this->postTypeSlug   = $slug;
		$this->postTypeArgs   = $args;
		$this->postTypeLabels = $labels;

		// Register the post type, if the post type does not already exist.
		if ( ! \post_type_exists( $this->postTypeName ) ) {

			// Registering the Custom Post Type.
			\add_action( 'init', [ $this, 'register' ] );

			// Custom messages.
			\add_filter( 'post_updated_messages', [ $this, 'messages' ] );

			// Custom Title placeholders.
			\add_filter( 'enter_title_here', [ $this, 'placeholders' ], 0, 2 );
		}
	}

	/**
	 * Method to register the post type.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function register() {

		// Capitalize the words and make it plural.
		$name   = Helpers::beautify( $this->postTypeName );
		$plural = Helpers::pluralize( $name );

		// Setting the default labels.
		$labels = array_merge(
			[
				/* translators: %s: post type plural name */
				'name'               => sprintf( esc_html_x( '%s', 'post type general name', 'my-plugin-text-domain' ), $plural ),
				/* translators: %s: post type singular name */
				'singular_name'      => sprintf( esc_html_x( '%s', 'post type singular name', 'my-plugin-text-domain' ), $name ),
				/* translators: %s: post type name */
				'add_new'            => sprintf( esc_html_x( 'Add New', '%s', 'my-plugin-text-domain' ), strtolower( $name ) ),
				/* translators: %s: post type name */
				'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'my-plugin-text-domain' ), $name ),
				/* translators: %s: post type name */
				'edit_item'          => sprintf( esc_html__( 'Edit %s', 'my-plugin-text-domain' ), $name ),
				/* translators: %s: post type name */
				'new_item'           => sprintf( esc_html__( 'New %s', 'my-plugin-text-domain' ), $name ),
				/* translators: %s: post type plural name */
				'all_items'          => sprintf( esc_html__( 'All %s', 'my-plugin-text-domain' ), $plural ),
				/* translators: %s: post type name */
				'view_item'          => sprintf( esc_html__( 'View %s', 'my-plugin-text-domain' ), $name ),
				/* translators: %s: post type plural name */
				'search_items'       => sprintf( esc_html__( 'Search %s', 'my-plugin-text-domain' ), $plural ),
				/* translators: %s: post type plural name */
				'not_found'          => sprintf( esc_html__( 'No %s found', 'my-plugin-text-domain' ), strtolower( $plural ) ),
				/* translators: %s: post type plural name */
				'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash', 'my-plugin-text-domain' ), strtolower( $plural ) ),
				/* translators: %s: post type plural name */
				'parent_item_colon'  => sprintf( esc_html__( 'Parent %s: ', 'my-plugin-text-domain' ), $plural ),
				'menu_name'          => $name,
			],
			$this->postTypeLabels
		);

		// Setting the default arguments.
		$args = array_merge(
			[
				'label'              => $plural,
				'labels'             => $labels,
				'menu_icon'          => 'dashicons-admin-customizer',
				'public'             => true,
				'show_ui'            => true,
				'has_archive'        => true,
				'publicly_queryable' => true,
				'query_var'          => true,
				'rewrite'            => true,
				'capability-type'    => 'post',
				'hierarchical'       => true,
				'show_in_rest'       => true,
				'supports'           => [
					'title',
					'editor',
					'excerpt',
					'thumbnail',
				],
				'show_in_nav_menus'  => true,
				'menu_position'      => 30,
			],
			$this->postTypeArgs
		);

		// Register the post type.
		\register_post_type( $this->postTypeSlug, $args );
	}

	/**
	 * Method to show custom messages.
	 *
	 * @param mixed $messages default messages.
	 *
	 * @return mixed modified messages.
	 * @since  1.0.0
	 */
	public function messages( $messages ) {
		$post           = \get_post();
		$postType       = \get_post_type( $post );
		$postTypeObject = \get_post_type_object( $postType );
		$postTypeName   = Helpers::beautify( $this->postTypeSlug );

		$messages[ $this->postTypeSlug ] = [
			0  => '',
			/* translators: %s: post type name */
			1  => sprintf( esc_html__( '%s updated.', 'my-plugin-text-domain' ), $postTypeName ),
			2  => esc_html__( 'Custom field updated.', 'my-plugin-text-domain' ),
			3  => esc_html__( 'Custom field deleted.', 'my-plugin-text-domain' ),
			/* translators: %s: post type name */
			4  => sprintf( esc_html__( '%s updated.', 'my-plugin-text-domain' ), $postTypeName ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( '%1$s restored to revision from %2$s', 'my-plugin-text-domain' ), $postTypeName, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: post type name */
			4  => sprintf( esc_html__( '%s published.', 'my-plugin-text-domain' ), $postTypeName ),
			/* translators: %s: post type name */
			4  => sprintf( esc_html__( '%s saved.', 'my-plugin-text-domain' ), $postTypeName ),
			/* translators: %s: post type name */
			4  => sprintf( esc_html__( '%s submitted.', 'my-plugin-text-domain' ), $postTypeName ),
			9  => sprintf(
				/* translators: Publish box date format. */
				__( '%1$s scheduled for: <strong>%2$s</strong>.', 'my-plugin-text-domain' ),
				$postTypeName,
				date_i18n( esc_html__( 'M j, Y @ G:i', 'my-plugin-text-domain' ), strtotime( $post->post_date ) )
			),
			/* translators: %s: post type name */
			10 => sprintf( esc_html__( '%s draft updated.', 'my-plugin-text-domain' ), $postTypeName ),
		];

		if ( $postTypeObject->publicly_queryable && $this->postTypeSlug === $postType ) {
			$permalink = get_permalink( $post->ID );

			/* translators: %s: URL of View Post & Name of the Custom Post Type */
			$view_link                 = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), sprintf( esc_html__( 'View ', 'my-plugin-text-domain' ), $postTypeName ) );
			$messages[ $postType ][1] .= $view_link;
			$messages[ $postType ][6] .= $view_link;
			$messages[ $postType ][9] .= $view_link;

			/* translators: %s: URL of Preview Post & Name of the Custom Post Type */
			$preview_permalink          = add_query_arg( 'preview', 'true', $permalink );
			$preview_link               = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), sprintf( esc_html__( 'Preview ', 'my-plugin-text-domain' ), $postTypeName ) );
			$messages[ $postType ][8]  .= $preview_link;
			$messages[ $postType ][10] .= $preview_link;
		}

		return $messages;
	}

	/**
	 * Method to show custom title placeholders.
	 *
	 * @param mixed $title default title.
	 * @param mixed $post post object.
	 *
	 * @return mixed modified placeholder.
	 * @since  1.0.0
	 */
	public function placeholders( $title, $post ) {
		$postTypeName = $this->postTypeSlug;
		$name         = Helpers::beautify( $postTypeName );

		if ( $postTypeName === $post->post_type ) {
			/* translators: post type name */
			$new_title = sprintf( esc_html__( 'Enter %s Title', 'my-plugin-text-domain' ), $name );
			return $new_title;
		}

		return $title;
	}
}
