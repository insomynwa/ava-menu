<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_astra_scripts', 0 );
add_action( 'wp_enqueue_scripts', 'ava_menu_astra_styles', 0 );

/**
 * Enqueue astra compatibility script
 *
 * @return void
 */
function ava_menu_astra_scripts() {
	wp_enqueue_script(
		'ava-menu-astra',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}

/**
 * Enqueue astra compatibility styles
 *
 * @return void
 */
function ava_menu_astra_styles() {
	wp_enqueue_style(
		'ava-menu-astra',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}
