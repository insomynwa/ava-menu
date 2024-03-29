<?php
/**
 * Ava Menu Polylang compatibility
 */

add_filter( 'ava-menu/public-manager/menu-location', 'ava_menu_polylang_fix_location' );

/**
 * Fix menu location for Polylang plugin
 *
 * @param  string $location Default location
 * @return string
 */
function ava_menu_polylang_fix_location( $location ) {

	// Ensure Polylang is active.
	if ( ! function_exists( 'PLL' ) || ! PLL() instanceof PLL_Frontend ) {
		return $location;
	}

	$new_location = PLL()->nav_menu->combine_location( $location, PLL()->curlang );

	return $new_location;

}