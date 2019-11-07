<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_twentysixteen_scripts', 0 );

/**
 * Enqueue twentysixteen compatibility script
 *
 * @return void
 */
function ava_menu_twentysixteen_scripts() {
	wp_enqueue_script(
		'ava-menu-twentysixteen',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}
