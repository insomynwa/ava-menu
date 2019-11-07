<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_hfe_styles', 0 );

/**
 * Enqueue header-footer-elementor compatibility styles
 *
 * @return void
 */
function ava_menu_hfe_styles() {
	wp_enqueue_style(
		'ava-menu-hfe',
		ava_menu()->plugin_url( 'integration/plugins/header-footer-elementor/assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}
