<?php
/**
 * General Class: Post Types.
 *
 * This class creates the necessary custom post tyles.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

namespace Prefix\MyPluginBoilerplate\Controllers\General;

use Prefix\MyPluginBoilerplate\Common\Models\CustomPostType;
use Prefix\MyPluginBoilerplate\Common\Models\CustomTaxonomy;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * General Class: Post Types.
 *
 * @since 1.0.0
 */
class PostTypes extends Base {

	/**
	 * Singleton Trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Accumulates Custom Post Types.
	 *
	 * @var array
	 * @since  1.0.0
	 */
	public $postTypes = [];

	/**
	 * Accumulates Custom Taxonomies.
	 *
	 * @var array
	 * @since  1.0.0
	 */
	public $taxonomies = [];

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
		$this->definePostTypes();
		$this->defineTax();

		if ( ! empty( $this->postTypes ) ) {
			$this->registerPostTypes();
		}

		if ( ! empty( $this->customTaxonomies ) ) {
			$this->registerTaxonomies();
		}
	}

	/**
	 * Method to define Post Types.
	 *
	 * @return array
	 * @since  1.0.0
	 */
	private function definePostTypes() {
		$this->postTypes = [
			[
				'name'   => __( 'Gallery', 'my-plugin-text-domain' ),
				'slug'   => 'test_gallery',
				'labels' => [
					'all_items' => __( 'All Galleries', 'my-plugin-text-domain' ),
				],
				'args'   => [
					'menu_icon'          => 'dashicons-format-gallery',
					'publicly_queryable' => false,
					'has_archive'        => false,
					'supports'           => [
						'title',
					],
				],
			],

			[
				'name'   => __( 'Testimonials', 'my-plugin-text-domain' ),
				'slug'   => 'test_testimonial',
				'labels' => [
					'all_items' => __( 'All Testimonials', 'my-plugin-text-domain' ),
				],
				'args'   => [
					'publicly_queryable' => false,
					'has_archive'        => false,
					'supports'           => [
						'title',
						'thumbnail',
					],
				],
			],
		];

		return $this->postTypes;
	}

	/**
	 * Method to define Taxonomies.
	 *
	 * @return array
	 * @since  1.0.0
	 */
	private function defineTax() {
		$this->customTaxonomies = [
			[
				'name'     => __( 'Gallery Type', 'my-plugin-text-domain' ),
				'cpt_name' => [ 'test_gallery' ],
				'slug'     => 'test_gallery_type',
				'labels'   => [
					'menu_name' => __( 'Types', 'my-plugin-text-domain' ),
				],
				'args'     => [
					'hierarchical' => true,
					'rewrite'      => [
						'slug' => 'type',
					],
				],
			],
		];

		return $this->customTaxonomies;
	}

	/**
	 * Method to loop through all the CPT definitions and build up CPT.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function registerPostTypes() {
		foreach ( $this->postTypes as $postType ) {
			new CustomPostType(
				$postType['name'],
				$postType['slug'],
				! empty( $postType['labels'] ) ? $postType['labels'] : [],
				! empty( $postType['args'] ) ? $postType['args'] : []
			);
		}
	}

	/**
	 * Method to loop through all the Tax definitions and build up Taxonomies.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function registerTaxonomies() {
		foreach ( $this->customTaxonomies as $customTax ) {
			new CustomTaxonomy(
				$customTax['name'],
				$customTax['cpt_name'],
				$customTax['slug'],
				! empty( $customTax['labels'] ) ? $customTax['labels'] : [],
				! empty( $customTax['args'] ) ? $customTax['args'] : []
			);
		}
	}
}
