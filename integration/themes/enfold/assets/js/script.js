( function( $ ) {
	jQuery( '.ava-menu' ).on( 'avaMenuCreated', function() {
		$( this ).closest( '.main_menu' ).removeClass( 'main_menu' ).addClass( 'ava_main_menu' );
		$( this ).closest( '.avia-menu' ).removeClass( 'avia-menu av-main-nav-wrap' );
	} );
}( jQuery ) );