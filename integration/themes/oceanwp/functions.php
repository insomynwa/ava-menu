<?php

add_filter( 'wp_nav_menu_items', 'ava_menu_oceanwp_fix_header_search', 999, 2 );
add_filter( 'wp_nav_menu_args', 'ava_menu_oceanwp_fix_menu_args', 100000 );
add_action( 'wp_enqueue_scripts', 'ava_menu_oceanwp_styles', 999 );


/**
 * Make header search in OceanWP theme compatible with AvaMenu
 * @return [type] [description]
 */
function ava_menu_oceanwp_fix_header_search( $items, $args ) {
	if ( ! isset( $args->menu_class ) || 'ava-menu' !== $args->menu_class ) {
		return $items;
	}

	$items = str_replace(
		array(
			'search-toggle-li',
			'site-search-toggle',
		),
		array(
			'search-toggle-li ava-menu-item ava-simple-menu-item ava-regular-item ava-responsive-menu-item',
			'site-search-toggle top-level-link',
		),
		$items
	);

	return $items;

}

/**
 * Fix nav menu arguments
 * @return array
 */
function ava_menu_oceanwp_fix_menu_args( $args ) {

	if ( ! isset( $args['menu_class'] ) || 'ava-menu' !== $args['menu_class'] ) {
		return $args;
	}

	$args['link_before'] = '';
	$args['link_after']  = '';

	return $args;
}

/**
 * Enqueue oceanwp compatibility styles
 *
 * @return void
 */
function ava_menu_oceanwp_styles() {
	wp_enqueue_style(
		'ava-menu-oceanwp',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}
