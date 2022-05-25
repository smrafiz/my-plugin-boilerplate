<?php
/**
 * Witgets Class: MediaWidget
 *
 * Creates a Media Widget.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Integrations\Widgets;

use WP_Widget;
use Prefix\MyPluginBoilerplate\Config\Plugin;
use Prefix\MyPluginBoilerplate\Common\Traits\Singleton;

/**
 * Class: Media Widget
 *
 * @since 1.0.0
 */
class MediaWidget extends WP_Widget {

	/**
	 * Singleton trait.
	 *
	 * @see Singleton
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Data from the plugin config class.
	 *
	 * @var array
	 * @see Plugin
	 * @since 1.0.0
	 */
	protected $plugin = [];

	/**
	 * Default instance.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $default_instance = [
		'title'   => '',
		'content' => '',
	];

	/**
	 * Widget ID.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $widgetID;

	/**
	 * Widget name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $widgetName;

	/**
	 * Widget options.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $widgetOptions = [];

	/**
	 * Control options.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $controlOptions = [];

	/**
	 * Registers the class.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		/**
		 * Integration classes instantiates before anything else
		 *
		 * @see Bootstrap::registerServices
		 *
		 * Widget is registered via the controllers/general/widgets class,
		 * but it is also possible to register from this class.
		 * @see Widgets
		 */

		$this->plugin = Plugin::instance();

		$this->widgetID   = 'my_plugin_boilerplate_media_widget';
		$this->widgetName = __( 'Media Widget', 'my-plugin-text-domain' );

		$this->widgetOptions = [
			'classname'                   => $this->widgetID,
			'description'                 => __( 'Diplays an image from My Plugin Boilerplate.', 'my-plugin-text-domain' ),
			'customize_selective_refresh' => true,
		];

		$this->controlOptions = [];

		parent::__construct(
			$this->widgetID,
			$this->widgetName,
			$this->widgetOptions,
			$this->controlOptions
		);
	}

	/**
	 * Sets up a new Media widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register();
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args Default widget arguments.
	 * @param array $instance Settings for the current instance.
	 * @return void
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		$instance = array_merge( $this->default_instance, $instance );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . \apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['image'] ) ) {
			$imageId = attachment_url_to_postid( $instance['image'] );

			$imageSize = 'full';
			$altText   = trim( wp_strip_all_tags( get_post_meta( $imageId, '_wp_attachment_image_alt', true ) ) );

			echo wp_get_attachment_image(
				$imageId,
				$imageSize,
				false,
				[
					'class' => 'media-image',
					'alt'   => esc_attr( $altText ),
				]
			);
		}

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance.
	 * @param array $old_instance Old settings for this instance.
	 * @return array $instance     Settings to save or bool false to cancel saving.
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ): array {
		$instance          = array_merge( $this->default_instance, $old_instance );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['image'] = ! empty( $new_instance['image'] ) ? $new_instance['image'] : '';

		return $instance;
	}

	/**
	 * Outputs the Media widget settings form.
	 *
	 * @param array $instance Current widget instance.
	 * @return void
	 * @since 1.0.0
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->default_instance );
		$title    = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Custom Text', 'my-plugin-text-domain' );
		$image    = ! empty( $instance['image'] ) ? $instance['image'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'my-plugin-text-domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_attr_e( 'Image:', 'my-plugin-text-domain' ); ?></label>
			<input class="widefat image-upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_url( $image ); ?>">
			<button type="button" class="button button-primary js-image-upload">Select Image</button>
		</p>
		<?php
	}
}
