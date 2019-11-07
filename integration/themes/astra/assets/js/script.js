( function( $ ) {
	jQuery( '.ava-menu' ).on( 'avaMenuCreated', function() {
		$( this ).closest( '.main-navigation' ).removeClass( 'main-navigation' );
	} );
}( jQuery ) );