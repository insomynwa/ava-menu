<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_filter( 'wp_nav_menu_args', 'ava_menu_betheme_fix_menu_args', 100000 );
add_action( 'wp_enqueue_scripts', 'ava_menu_betheme_scripts', 0 );

/**
 * Fix nav menu arguments
 * @return array
 */
function ava_menu_betheme_fix_menu_args( $args ) {

	if ( ! isset( $args['menu_class'] ) || 'ava-menu' !== $args['menu_class'] ) {
		return $args;
	}

	$args['link_before'] = '';
	$args['link_after']  = '';

	return $args;
}

/**
 * Enqueue enfold compatibility script
 *
 * @return void
 */
function ava_menu_betheme_scripts() {
	wp_enqueue_script(
		'ava-menu-betheme',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}
