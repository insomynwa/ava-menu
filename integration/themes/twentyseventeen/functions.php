<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_twentyseventeen_scripts', 0 );

/**
 * Enqueue twentyseventeen compatibility script
 *
 * @return void
 */
function ava_menu_twentyseventeen_scripts() {
	wp_enqueue_script(
		'ava-menu-twentyseventeen',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}
