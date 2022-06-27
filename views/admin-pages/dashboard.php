<?php
/**
 * Admin View: Dashboard
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

?>

<div class="wrap">
	<h1><?php echo esc_html__( 'My Plugin Boilerplate', 'my-plugin-text-domain' ); ?></h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php
			settings_fields( 'my_plugin_boilerplate_settings' );
			do_settings_sections( 'my_plugin_boilerplate' );
			submit_button();
		?>
	</form>
</div>
