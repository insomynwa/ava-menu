<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_phlox_styles', 0 );

add_action( 'elementor/widget/before_render_content', 'ava_menu_remove_phlox_nav_menu_filters' );
add_filter( 'elementor/widget/render_content',        'ava_menu_add_phlox_nav_menu_filters', 10, 2 );

/**
 * Enqueue Phlox compatibility styles
 *
 * @return void
 */
function ava_menu_phlox_styles() {
	wp_enqueue_style(
		'ava-menu-phlox',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}

/**
 * Remove phlox nav-menu filters before render the Vertical Mega Menu Widget
 */
function ava_menu_remove_phlox_nav_menu_filters( $widget ) {
	if ( ! in_array( $widget->get_name(), array( 'ava-mega-menu', 'ava-custom-menu' ) ) ) {
		return;
	}


	if ( ! class_exists( 'Auxin_Master_Nav_Menu' ) ) {
		return;
	}

	$auxin_nav_menu = Auxin_Master_Nav_Menu::get_instance();

	remove_filter( 'wp_nav_menu_args', array( $auxin_nav_menu, 'change_nav_menu_frontend_walker' ), 9 );
}

/**
 * Add phlox nav-menu filters after render the Vertical Mega Menu Widget
 */
function ava_menu_add_phlox_nav_menu_filters( $content, $widget ) {

	if ( ! in_array( $widget->get_name(), array( 'ava-mega-menu', 'ava-custom-menu' ) ) ) {
		return $content;
	}

	if ( ! class_exists( 'Auxin_Master_Nav_Menu' ) ) {
		return $content;
	}

	$auxin_nav_menu = Auxin_Master_Nav_Menu::get_instance();

	add_filter( 'wp_nav_menu_args', array( $auxin_nav_menu, 'change_nav_menu_frontend_walker' ), 9, 1 );

	return $content;
}
