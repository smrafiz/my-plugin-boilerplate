<?php
/**
 * Template: Test Template
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

?>
<p>
	<?php
	echo esc_html__( 'This is being loaded inside "wp_footer" from the templates class', 'my-plugin-text-domain' ) . ' ' . esc_html( $args['data']['text'] );
	?>
</p>
