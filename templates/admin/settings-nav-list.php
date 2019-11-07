<?php

foreach ( $settings_stack as $key => $args ) {

	$args['value'] = isset( $saved_settings[ $location ][ $key ] ) ? $saved_settings[ $location ][ $key ] : $args['value'];
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' ava-menu-control' : 'ava-menu-control';
	$args['id']    = sprintf( '%1$s-%2$s', $location, $args['id'] );
	$args['name']  = sprintf( '%1$s[%2$s]', $location, $args['name'] );
	$instance      = ava_menu()->ui()->get_ui_element_instance( $args['type'], $args );

	printf( '<div>%s</div>', $instance->render() );
}