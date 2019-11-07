<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_zerif_styles', 0 );

/**
 * Enqueue zerif compatibility styles
 *
 * @return void
 */
function ava_menu_zerif_styles() {
	wp_enqueue_style(
		'ava-menu-zerif',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}
