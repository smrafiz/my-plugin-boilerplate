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

		foreach ( $this->metaBoxes as $key => $metaBox ) {
			if ( isset( $metaBox['fields'] ) ) {
				foreach ( $metaBox['fields'] as $fieldId => $field ) {
					$this->saveMetaFields( $postID, $fieldId, $field );
				}
			} elseif ( isset( $metaBox['groups'] ) ) {
				if ( ! empty( $metaBox['groups'] ) ) {
					foreach ( $metaBox['groups'] as $key => $group ) {
						foreach ( $group['fields'] as $fieldId => $field ) {
							$this->saveMetaFields( $postID, $fieldId, $field );
						}
					}
				}
			}
		}
	}

	/**
	 * Saves Meta Fields.
	 *
	 * @param int    $id Post ID.
	 * @param string $metaKey Meta Key.
	 * @param array  $field Meta Field.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function saveMetaFields( $id, $metaKey, $field ) {
		$rawValue  = isset( $_REQUEST[ $metaKey ] ) ? wp_unslash( $_REQUEST[ $metaKey ] ) : null;
		$metaValue = Helpers::sanitize( $field, $rawValue );

		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $id, $metaKey, 'yes' );
		} else {
			update_post_meta( $id, $metaKey, $metaValue );
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
		$groups = isset( $metaBox['args']['groups'] ) ? $metaBox['args']['groups'] : [];

		if ( ! empty( $fields ) ) {
			$this->generateFields( $post->ID, $fields, $metaBox );
		}

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				$this->generateGroupFields( $post->ID, $group, $fields, $metaBox );
			}
		}
	}

	/**
	 * Generate Fields Markup.
	 *
	 * @param int   $id Post ID.
	 * @param array $fields Fields.
	 * @param array $metaBox Metabox.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	private function generateFields( $id, $fields, $metaBox ) {
		?>
		<div class="widefat my-plugin-boilerplate-fields-wrapper">
			<div class="my-plugin-boilerplate-field-inner">
				<?php
				foreach ( $fields as $metaKey => $field ) {
					$this->generateMarkup( $id, $metaKey, $field, $metaBox );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Generate Group Fields Markup.
	 *
	 * @param int   $id Post ID.
	 * @param array $group Group.
	 * @param array $metaBox Metabox.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	private function generateGroupFields( $id, $group, $metaBox ) {
		?>
		<div class="my-plugin-boilerplate-fields-group">
			<div class="my-plugin-boilerplate-group-header">
				<h3 class="my-plugin-boilerplate-group-title"><?php echo esc_html( $group['title'] ); ?></h3>
				<p class="my-plugin-boilerplate-group-description">
					<?php
					echo wp_kses( $group['description'], Helpers::allowedTags() );
					?>
				</p>
			</div>

			<div class="my-plugin-boilerplate-group-body">
				<?php
				if ( ! empty( $group['fields'] ) ) {
					$this->generateFields( $id, $group['fields'], $metaBox );
				}
				?>
			</div>
		</div>
		<?php
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
	public function getMeta( $metaKey = '', $postID = null ) {
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
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function generateMarkup( $postID, $metaKey = '', $field = [], $metaBox ) {
		$duplicateClass   = in_array( $metaKey, $this->duplicateKeys, true ) ? ' my-plugin-boilerplate-duplicate-field' : '';
		$readonly         = in_array( $metaKey, $this->duplicateKeys, true ) ? 'readonly' : '';
		$duplicateMessage = '';

		if ( in_array( $metaKey, $this->duplicateKeys, true ) ) {
			$this->generateNotice( $metaKey, $metaBox['title'] );
		}

		$value = get_post_meta( $postID, $metaKey, true );

		if ( empty( $value ) ) {
			$value = isset( $field['default'] ) ? $field['default'] : '';
		}

		?>
		<div class="my-plugin-boilerplate-fields-row<?php echo esc_attr( $duplicateClass ); ?>">
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
				echo wp_kses( $duplicateMessage, Helpers::allowedTags() );
				switch ( $field['type'] ) {
					case 'text':
						?>
						<input type="text" <?php echo esc_attr( $readonly ); ?> name="<?php echo esc_attr( $metaKey ); ?>" value="<?php echo wp_kses( $value, Helpers::allowedTags() ); ?>" />
						<p class="description"><?php echo wp_kses( $field['description'], Helpers::allowedTags() ); ?></p>
						<?php
						break;
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Generates Duplicate Notice.
	 *
	 * @param string $key Meta Key.
	 * @param string $title Meta Title.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	private function generateNotice( $key, $title ) {
		?>
		<div class="notice notice-warning">
			<?php
			printf(
				'<p>%s: <code>%s</code> - <b>%s</b>. <p>%s</p>',
				esc_html__( 'Duplicate Meta key', 'my-plugin-text-domain' ),
				esc_html( $key ),
				esc_html( $title ),
				esc_html__( 'Please use unique meta key.', 'my-plugin-text-domain' )
			);
			?>
		</div>
		<?php
	}
}
