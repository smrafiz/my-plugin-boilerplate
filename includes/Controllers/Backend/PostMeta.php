<?php
/**
 * Backend Class: PostMeta
 *
 * This class creates the necessary admin pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Controllers\Backend;

use Prefix\MyPluginBoilerplate\Common\Models\MetaFields;

use Prefix\MyPluginBoilerplate\Common\
{
	Abstracts\Base,
	Traits\Singleton
};

/**
 * Backend Class: PostMeta
 *
 * @since 1.0.0
 */
class PostMeta extends Base {

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
		$metaFields = new MetaFields();

		$metaFields->addMetaBox( $this->metaboxes() );
	}

	/**
	 * Method to accumulate Post metas.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function metaboxes() {
		return [
			$this->galleryMeta(),
			$this->testimonialMeta(),
			$this->sides(),
		];
	}

	private function galleryMeta() {
		return [
			'id'       => 'my-plugin-boilerplate-example-text-field',
			'title'    => __( 'Text Field', 'my-plugin-text-domain' ),
			'screen'   => [ 'test_gallery', 'test_testimonial' ],
			'context'  => 'normal',
			'priority' => 'high',
			'groups'   => [
				[
					'title'       => 'Group 1',
					'description' => 'Group Description',
					'fields'      => [
						'prefix-group-1-text'     => [
							'type'        => 'text',
							'title'       => 'Text Field',
							'description' => 'Text Field field description goes here.',
							'hint'        => 'Text Field field description goes here.',
							'default'     => '',
						],
						'prefix-group-1-textarea' => [
							'type'        => 'text',
							'title'       => 'Textarea',
							'description' => 'Textarea field description goes here.',
							'hint'        => 'Textarea field description goes here.',
							'default'     => '',
						],
					],
				],
				[
					'title'       => 'Group 2',
					'description' => 'Group Description',
					'fields'      => [
						'prefix-group-2-text'     => [
							'type'        => 'text',
							'title'       => 'Text Field',
							'description' => 'Text Field field description goes here.',
							'hint'        => 'Text Field field description goes here.',
							'default'     => '',
						],
						'prefix-group-2-textarea' => [
							'type'        => 'text',
							'title'       => 'Textarea',
							'description' => 'Textarea field description goes here.',
							'hint'        => 'Textarea field description goes here.',
							'default'     => '',
						],
					],
				],
			],
		];
	}

	private function testimonialMeta() {
		return [
			'id'       => 'my-plugin-boilerplate-example-text-field-2',
			'title'    => __( 'Text Field 2', 'my-plugin-text-domain' ),
			'screen'   => [ 'test_gallery', 'test_testimonial' ],
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => [
				'my-plugin-boilerplate-example-text-3' => [
					'type'        => 'text',
					'title'       => __( 'Text Field', 'my-plugin-text-domain' ),
					'description' => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'hint'        => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'default'     => '',
				],
				'my-plugin-boilerplate-example-text-4' => [
					'type'        => 'text',
					'title'       => __( 'Text Field 2', 'my-plugin-text-domain' ),
					'description' => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'hint'        => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'default'     => '',
				],
			],
		];
	}

	private function sides() {
		return [
			'id'       => 'my-plugin-boilerplate-example-text-field-3',
			'title'    => __( 'Text Field 3', 'my-plugin-text-domain' ),
			'screen'   => [ 'test_gallery', 'test_testimonial' ],
			'context'  => 'side',
			'priority' => 'default',
			'fields'   => [
				'my-plugin-boilerplate-example-text-5' => [
					'type'        => 'text',
					'title'       => __( 'Text Field', 'my-plugin-text-domain' ),
					'description' => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'hint'        => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'default'     => '',
				],
				'my-plugin-boilerplate-example-text-6' => [
					'type'        => 'text',
					'title'       => __( 'Text Field 2', 'my-plugin-text-domain' ),
					'description' => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'hint'        => __( 'Text Field field description goes here.', 'my-plugin-text-domain' ),
					'default'     => '',
				],
			],
		];
	}
}
