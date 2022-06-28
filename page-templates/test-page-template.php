<?php
/**
 * Template Name: Test Page Template
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

get_header();

echo esc_html__( 'Greetings from Plugin', 'my-plugin-text-domain' );

the_content();

get_footer();
