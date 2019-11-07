( function( $ ) {
	jQuery( '.ava-menu' ).on( 'avaMenuCreated', function() {
		$( this )
			.removeClass( 'nav-menu' )
			.closest( '.main-navigation' ).removeClass( 'main-navigation' )
			.find( '> .menu' ).css( 'display', 'none' );
	} );
}( jQuery ) );