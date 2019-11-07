<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_storefront_scripts', 0 );

/**
 * Enqueue storefront compatibility script
 *
 * @return void
 */
function ava_menu_storefront_scripts() {
	wp_enqueue_script(
		'ava-menu-storefront',
		ava_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		ava_menu()->get_version(),
		true
	);
}
