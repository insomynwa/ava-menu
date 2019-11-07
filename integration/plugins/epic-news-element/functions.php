<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'admin_enqueue_scripts', 'ava_menu_epic_ne_admin_scripts', 99 );

function ava_menu_epic_ne_admin_scripts( $hook ) {
	if ( in_array( $hook, array( 'toplevel_page_ava-menu', 'nav-menus.php' ) ) ) {
		wp_dequeue_script( 'bootstrap-iconpicker' );
	}
}
