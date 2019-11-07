<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'ava_menu_avada_styles', 0 );
add_filter( 'wp_nav_menu_items', 'ava_menu_avada_fix_header_search', 999, 2 );
add_filter( 'ava-menu/main-walker/end-el', 'ava_menu_avada_middle_logo', 10, 5 );
add_filter( 'ava-menu/set-menu-args/', 'ava_menu_avada_mobile_middle_logo' );

/**
 * Make header search in avada theme compatible with AvaMenu
 * @return [type] [description]
 */
function ava_menu_avada_fix_header_search( $items, $args ) {
	if ( ! isset( $args->menu_class ) || 'ava-menu' !== $args->menu_class ) {
		return $items;
	}

	$items = str_replace(
		array(
			'fusion-custom-menu-item fusion-main-menu-search',
			'fusion-main-menu-icon',
		),
		array(
			'fusion-custom-menu-item fusion-main-menu-search ava-menu-item ava-simple-menu-item ava-regular-item ava-responsive-menu-item',
			'fusion-main-menu-icon top-level-link',
		),
		$items
	);

	return $items;

}

/**
 * Enqueue avada compatibility styles
 *
 * @return void
 */
function ava_menu_avada_styles() {
	wp_enqueue_style(
		'ava-menu-avada',
		ava_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		ava_menu()->get_version()
	);
}

/**
 * Adds middle logo if this layout is selected
 *
 * @return string
 */
function ava_menu_avada_middle_logo( $output, $item, $walker, $depth, $args ) {

	if ( '0' !== $item->menu_item_parent ) {
		return $output;
	}

	$avada = Avada()->settings;

	if ( 'v7' !== $avada->get( 'header_layout' ) || 'Top' !== $avada->get( 'header_position' ) ) {
		return $output;
	}

	if ( ! isset( $walker->items_count ) ) {

		$items = wp_get_nav_menu_items( $args->menu );
		$count = 0;

		foreach ( $items as $item ) {
			if ( '0' === $item->menu_item_parent ) {
				$count++;
			}
		}

		$walker->items_count = $count;
	}

	if ( ! isset( $walker->item_index ) ) {
		$walker->item_index = 0;
	}

	$walker->item_index++;

	if ( absint( $walker->item_index ) === absint( ceil( $walker->items_count / 2 ) ) ) {
		ob_start();
		get_template_part( 'templates/logo' );
		$output .= ob_get_clean();
	}

	return $output;
}

/**
 * Add mobile fallback for middle logo
 *
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
function ava_menu_avada_mobile_middle_logo( $args ) {

	$avada = Avada()->settings;

	if ( 'v7' === $avada->get( 'header_layout' ) && 'Top' === $avada->get( 'header_position' ) ) {
		ob_start();
		get_template_part( 'templates/logo' );
		$logo = ob_get_clean();

		$logo = str_replace(
			array( '<li', 'li>' ),
			array( '<div', 'div>' ),
			$logo
		);

		echo $logo;

	}

	return $args;
}
