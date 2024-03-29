<?php

add_filter( 'wp_nav_menu', 'ava_menu_fix_tribe_content', 10, 2 );
add_filter( 'parse_request', 'ava_menu_check_tribe_template', 999 );
add_filter( 'tribe_events_after_html', 'ava_menu_fix_after_tribe_content' );

function ava_menu_check_tribe_template( $query ) {
	if ( ! isset( $query->query_vars['post_type'] ) ) {
		return;
	}

	if ( 'tribe_events' === $query->query_vars['post_type'] ) {
		wp_cache_add( 'ava-menu-tribe-need-fix', true );
	}
}

/**
 * Prevent Events calendar from broking
 */
function ava_menu_fix_tribe_content( $nav_menu, $args ) {

	if ( ! isset( $args->menu_class ) || 'ava-menu' !== $args->menu_class ) {
		return $nav_menu;
	}

	if ( ! ava_menu_need_fix_tribe_content( $args ) ) {
		return $nav_menu;
	}

	Elementor\Plugin::instance()->frontend->remove_content_filter();

	return $nav_menu;

}

/**
 * Fix content after tribe
 *
 * @return [type] [description]
 */
function ava_menu_fix_after_tribe_content( $html ) {

	if ( ! ava_menu_need_fix_tribe_content() ) {
		return $html;
	}

	Elementor\Plugin::instance()->frontend->add_content_filter();
	wp_cache_delete( 'ava-menu-tribe-need-fix' );

	return $html;

}

/**
 * Check if tribe content need to be fixed
 *
 * @return [type] [description]
 */
function ava_menu_need_fix_tribe_content() {

	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	$need_fix = wp_cache_get( 'ava-menu-tribe-need-fix' );

	if ( true === $need_fix ) {
		return true;
	}

	if ( 'tribe_events' !== get_post_type() ) {
		return false;
	}

	return true;

}
