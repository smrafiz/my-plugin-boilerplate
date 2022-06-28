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

	/**
	 * Example Metabox.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function galleryMeta() {
		return [
			'id'       => 'my-plugin-boilerplate-example-text-field',
			'title'    => __( 'Sample Fields 1', 'my-plugin-text-domain' ),
			'screen'   => [ 'test_gallery', 'test_testimonial' ],
			'context'  => 'advanced',
			'priority' => 'high',
			'groups'   => [
				[
					'title'       => 'Group 1',
					'description' => 'Group Description',
					'fields'      => [
						'prefix-1-text'     => [
							'type'        => 'text',
							'title'       => 'Text Field',
							'description' => 'Text Field field description goes here.',
							'hint'        => 'Text Field field description goes here.',
							'default'     => '',
						],
						'prefix-1-textarea' => [
							'type'        => 'textarea',
							'title'       => 'Textarea',
							'description' => 'Textarea field description goes here.',
							'hint'        => 'Textarea field description goes here.',
							'default'     => '',
						],
						'prefix-1-password' => [
							'type'        => 'password',
							'title'       => 'Password',
							'description' => 'Password field description goes here.',
							'hint'        => 'Password field description goes here.',
							'default'     => '',
						],
						'prefix-1-color'    => [
							'type'        => 'color',
							'title'       => 'Color',
							'description' => 'Color field description goes here.',
							'hint'        => 'Color field description goes here.',
							'default'     => '#f3f3f3',
						],
						'prefix-1-date'     => [
							'type'        => 'date',
							'title'       => 'Date',
							'description' => 'Date field description goes here.',
							'hint'        => 'Date field description goes here.',
							'default'     => '',
						],
						'prefix-1-number'   => [
							'type'        => 'number',
							'title'       => 'Number',
							'description' => 'Number field description goes here.',
							'hint'        => 'Number field description goes here.',
							'default'     => '',
						],
						'prefix-1-email'    => [
							'type'        => 'email',
							'title'       => 'Email',
							'description' => 'Email field description goes here.',
							'hint'        => 'Email field description goes here.',
							'default'     => '',
						],
						'prefix-1-url'      => [
							'type'        => 'url',
							'title'       => 'Url',
							'description' => 'Url field description goes here.',
							'hint'        => 'Url field description goes here.',
							'default'     => '',
						],
						'prefix-1-checkbox' => [
							'type'        => 'checkbox',
							'title'       => 'Checkbox',
							'description' => 'Checkbox field description goes here.',
							'hint'        => 'Checkbox field description goes here.',
						],
						'prefix-2-checkbox' => [
							'type'        => 'checkbox',
							'title'       => 'Checkbox',
							'description' => 'Checkbox field description goes here.',
							'hint'        => 'Checkbox field description goes here.',
						],
						'prefix-3-checkbox' => [
							'type'        => 'checkbox',
							'title'       => 'Checkbox',
							'description' => 'Checkbox field description goes here.',
							'hint'        => 'Checkbox field description goes here.',
						],
						'prefix-1-radio'    => [
							'type'        => 'radio',
							'title'       => 'Radio',
							'description' => 'Radio field description goes here.',
							'hint'        => 'Radio field description goes here.',
							'default'     => 'one',
							'choices'     => [
								'one'   => 'One',
								'two'   => 'Two',
								'three' => 'Three',
							],
						],
						'prefix-1-select'   => [
							'type'        => 'select',
							'title'       => 'Select',
							'description' => 'Select field description goes here.',
							'hint'        => 'Select field description goes here.',
							'default'     => 'one',
							'choices'     => [
								'one'   => 'One',
								'two'   => 'Two',
								'three' => 'Three',
							],
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

	/**
	 * Example Metabox.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function testimonialMeta() {
		return [
			'id'       => 'my-plugin-boilerplate-example-text-field-2',
			'title'    => __( 'Sample Fields 2', 'my-plugin-text-domain' ),
			'screen'   => [ 'test_gallery', 'test_testimonial' ],
			'context'  => 'advanced',
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

	/**
	 * Example Metabox.
	 *
	 * @return array
	 * @since 1.0.0
	 */
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
