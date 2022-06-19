<?php
/**
 * Model Class: Meta Fields
 *
 * This class creates meta fields for posts & custom post types.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Models;

use Prefix\MyPluginBoilerplate\Common\Functions\Helpers;

/**
 * Model Class: Meta Fields
 *
 * @since 1.0.0
 */
class MetaFields {

	/**
	 * Meta Option
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $metaBoxes = [];

	/**
	 * Unique Keys
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $uniqueKeys = [];

	/**
	 * Duplicate Keys
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $duplicateKeys = [];

	/**
	 * Class Constructor.
	 *
	 * Registers Meta Fields.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function __construct() {
		\add_action( 'load-post.php', [ $this, 'register' ] );
		\add_action( 'load-post-new.php', [ $this, 'register' ] );
	}

	/**
	 * Registers Metaboxes.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function register() {
		\add_action( 'add_meta_boxes', [ $this, 'add' ] );
		\add_action( 'save_post', [ $this, 'save' ] );
	}

	/**
	 * Adds Metaboxes.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function add() {
		if ( empty( $this->metaBoxes ) ) {
			return;
		}

		$this->checkDuplicates();

		foreach ( $this->metaBoxes as $key => $metaBox ) {
			add_meta_box(
				$metaBox['id'],
				$metaBox['title'],
				[ $this, 'callback' ],
				$metaBox['screen'],
				$metaBox['context'],
				$metaBox['priority'],
				$metaBox
			);
		}
	}

	/**
	 * Saves Metaboxes
	 *
	 * @param number $postID Post ID.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function save( $postID ) {
		if ( empty( $this->metaBoxes ) ) {
			return;
		}

		// Checks save status.
		$isAutosave   = wp_is_post_autosave( $postID );
		$isRevision   = wp_is_post_revision( $postID );
		$isValidNonce = Helpers::verifyNonce();

		// Exits script depending on save status.
		if ( $isAutosave || $isRevision || ! $isValidNonce ) {
			return;
		}

		$fieldsByType    = [];
		$allFields       = [];
		$submittedFields = [];

		foreach ( $this->metaBoxes as $key => $metaBox ) {
			if ( isset( $metaBox['fields'] ) ) {
				foreach ( $metaBox['fields'] as $fieldId => $field ) {
					$fieldsByType[ $field['type'] ][] = $fieldId;
				}

				$current_fields = array_keys( $metaBox['fields'] );
				$allFields      = array_merge( $allFields, $current_fields );
			} elseif ( isset( $metaBox['groups'] ) ) {
				if ( ! empty( $metaBox['groups'] ) ) {
					foreach ( $metaBox['groups'] as $key => $group ) {
						$current_fields = array_keys( $group['fields'] );
						$allFields      = array_merge( $allFields, $current_fields );

						foreach ( $group['fields'] as $fieldId => $field ) {
							$fieldsByType[ $field['type'] ][] = $fieldId;
						}
					}
				}
			}
		}

		// error_log( print_r( $fieldsByType, true ),3, __DIR__ . "/log.txt") ;

		foreach ( $allFields as $fieldData ) {
			// phpcs:disable
			$submittedFields[ $fieldData ] = isset( $_REQUEST[ $fieldData ] ) ? Helpers::kses( wp_unslash( $_REQUEST[ $fieldData ] ) ) : null;
			// phpcs:enable
		}

		foreach ( $submittedFields as $currentKey => $currentValue ) {
			update_post_meta( $postID, $currentKey, $currentValue );
		}

		if ( isset( $fieldsByType['checkbox'] ) ) {
			foreach ( $fieldsByType['checkbox'] as $key => $checkbox ) {
				if ( ! in_array( $checkbox, array_keys( $submittedFields ), true ) ) {
					update_post_meta( $postID, $checkbox, 'no' );
				}
			}
		}
	}

	/**
	 * Metabox Callback
	 *
	 * @param object $post Post object.
	 * @param array  $metaBox Metabox.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function callback( $post, $metaBox ) {
		Helpers::createNonce();

		$fields = isset( $metaBox['args']['fields'] ) ? $metaBox['args']['fields'] : [];

		if ( ! empty( $fields ) ) {
			?>
				<div class="widefat my-plugin-boilerplate-fields-wrapper">
					<div class="my-plugin-boilerplate-field-inner">
						<?php
						foreach ( $fields as $metaKey => $field ) {
							$this->generateMarkup( $post->ID, $metaKey, $field, $metaBox );
						}
						?>
					</div>
				</div>
			<?php
		}

		$groups = isset( $metaBox['args']['groups'] ) ? $metaBox['args']['groups'] : [];

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				?>
				<div class="my-plugin-boilerplate-fields-group-wrapper">
					<div class="my-plugin-boilerplate-fields-title">
						<h3><?php echo esc_html( $group['title'] ); ?></h3>
						<p class="description">
							<?php
							echo Helpers::kses( $group['description'] ); // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</p>
					</div>

					<?php
					if ( ! empty( $group['fields'] ) ) {
						?>
						<div class="widefat my-plugin-boilerplate-fields-group">
							<div>
								<?php foreach ( $group['fields'] as $metaKey => $field ) { ?>
									<?php $this->generateMarkup( $post->ID, $metaKey, $field, $metaBox ); ?>
								<?php } ?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
		}

	}

	/**
	 * Checks for duplicates
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function checkDuplicates() {
		$allFields = [];

		foreach ( $this->metaBoxes as $key => $metaBox ) {
			if ( isset( $metaBox['fields'] ) ) {
				$allFields[] = array_keys( $metaBox['fields'] );
			} elseif ( isset( $metaBox['groups'] ) ) {
				if ( ! empty( $metaBox['groups'] ) ) {
					foreach ( $metaBox['groups'] as $key => $groupMetaBox ) {
						$allFields[] = array_keys( $groupMetaBox['fields'] );
					}
				}
			}
		}

		foreach ( $allFields as $key => $metaKeys ) {
			if ( is_array( $metaKeys ) ) {
				if ( ! empty( $metaKeys ) ) {
					foreach ( $metaKeys as $currentKey => $currentMetaKey ) {
						if ( in_array( $currentMetaKey, $this->uniqueKeys, true ) ) {
							$this->duplicateKeys[] = $currentMetaKey;
						} else {
							$this->uniqueKeys[] = $currentMetaKey;
						}
					}
				}
			}
		}
	}

	/**
	 * Adds Metabox.
	 *
	 * @param array $args Metabox args.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function addMetaBox( $args = [] ) {
		$this->metaBoxes = $args;
	}

	/**
	 * Get Meta.
	 *
	 * @param string $metaKey Meta Key.
	 * @param int    $postID Post ID.
	 *
	 * @return mixed
	 * @since  1.0.0
	 */
	public function get_meta( $metaKey = '', $postID = null ) {
		if ( empty( $postID ) ) {
			$postID = get_the_ID();
		}

		return get_post_meta( $postID, $metaKey, true );
	}

	/**
	 * Generates Input Markups.
	 *
	 * @param int    $postID Post ID.
	 * @param string $metaKey Meta Key.
	 * @param array  $field Field.
	 * @param array  $metaBox Metabox.
	 * @return void
	 */
	public function generateMarkup( $postID, $metaKey = '', $field = [], $metaBox ) {
		$duplicateClass   = in_array( $metaKey, $this->duplicateKeys, true ) ? 'my-plugin-boilerplate-field-duplicate' : '';
		$readonly         = in_array( $metaKey, $this->duplicateKeys, true ) ? 'readonly' : '';
		$duplicateMessage = '';
		if ( in_array( $metaKey, $this->duplicateKeys, true ) ) {
			?>
			<div class="notice notice-warning">
				<?php
				printf(
					'<p>%s: <code>%s</code> - <b>%s</b>. <p>%s</p>',
					esc_html__( 'Duplicate Meta key', 'my-plugin-text-domain' ),
					esc_html( $metaKey ),
					esc_html( $metaBox['title'] ),
					esc_html__( 'Please use unique meta key.', 'my-plugin-text-domain' )
				);
				?>
			</div>
			<?php
		}

		$value = get_post_meta( $postID, $metaKey, true );

		if ( empty( $value ) ) {
			$value = isset( $field['default'] ) ? $field['default'] : '';
		}

		switch ( $field['type'] ) {
			case 'text':
				?>
				<div class="my-plugin-boilerplate-fields-row <?php echo esc_attr( $duplicateClass ); ?>">
					<div class="my-plugin-boilerplate-fields-heading"><?php echo esc_html( $field['title'] ); ?>
						<?php
						if ( ! empty( $field['hint'] ) ) {
							?>
							<div class="my-plugin-boilerplate-fields-hint">
								<i class="dashicons dashicons-editor-help" onClick="jQuery(this).siblings('.my-plugin-boilerplate-fields-hint-message').fadeToggle(300);"></i>
								<p class="my-plugin-boilerplate-fields-hint-message" style="display: none;"><?php echo esc_html( $field ['hint'] ); ?></p>
							</div>
							<?php
						}
						?>
					</div>
					<div class="my-plugin-boilerplate-fields-content">
						<?php
						echo Helpers::kses( $duplicateMessage ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<input type="text" <?php echo esc_attr( $readonly ); ?> name="<?php echo $metaKey; ?>" value="<?php echo Helpers::kses( $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" />
						<p class="description"><?php echo Helpers::kses( $field['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					</div>
				</div>
				<?php
				break;
		}
	}
}
