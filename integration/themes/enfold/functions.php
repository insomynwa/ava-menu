<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_enfold_scripts', 0 );
add_action( 'wp_enqueue_scripts', 'ava_menu_enfold_styles', 0 );

/**
 * Enqueue enfold compatibility script
 *
 * @return void
 */
function ava_menu_enfold_scripts() {
	wp_enqueue_script(
		'ava-menu-enfold',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}

/**
 * Enqueue enfold compatibility styles
 *
 * @return void
 */
function ava_menu_enfold_styles() {
	wp_enqueue_style(
		'ava-menu-enfold',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}
