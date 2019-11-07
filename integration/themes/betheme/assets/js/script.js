( function( $ ) {
	jQuery( '.ava-menu' ).on( 'avaMenuCreated', function() {
		$( this ).closest( '#menu' ).removeAttr( 'id' ).removeAttr( 'class' );
		$( '.responsive-menu-toggle ' ).css( 'display', 'none' );
	} );
}( jQuery ) );