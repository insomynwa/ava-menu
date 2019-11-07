<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_generatepress_scripts', 0 );

/**
 * Enqueue generatepress compatibility script
 *
 * @return void
 */
function ava_menu_generatepress_scripts() {

	wp_enqueue_script(
		'ava-menu-generatepress',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);

}
