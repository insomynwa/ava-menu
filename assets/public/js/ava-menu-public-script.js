(function( $ ){
	'use strict';

	var avaMenu = {

		init: function() {
			var rollUp                   = false,
				avaMenuMouseleaveDelay   = 500,
				avaMenuMegaWidthType     = 'container',
				avaMenuMegaWidthSelector = '',
				avaMenuMegaOpenSubType   = 'hover',
				avaMenuMobileBreakpoint  = 768;

			if ( window.avaMenuPublicSettings && window.avaMenuPublicSettings.menuSettings ) {
				rollUp                   = ( 'true' === avaMenuPublicSettings.menuSettings.avaMenuRollUp ) ? true : false;
				avaMenuMouseleaveDelay   = avaMenuPublicSettings.menuSettings.avaMenuMouseleaveDelay || 500;
				avaMenuMegaWidthType     = avaMenuPublicSettings.menuSettings.avaMenuMegaWidthType || 'container';
				avaMenuMegaWidthSelector = avaMenuPublicSettings.menuSettings.avaMenuMegaWidthSelector || '';
				avaMenuMegaOpenSubType   = avaMenuPublicSettings.menuSettings.avaMenuMegaOpenSubType || 'hover';
				avaMenuMobileBreakpoint  = avaMenuPublicSettings.menuSettings.avaMenuMobileBreakpoint || 768;
			}

			$( '.ava-menu-container' ).AvaMenu( {
				enabled: rollUp,
				mouseLeaveDelay: +avaMenuMouseleaveDelay,
				megaWidthType: avaMenuMegaWidthType,
				megaWidthSelector: avaMenuMegaWidthSelector,
				openSubType: avaMenuMegaOpenSubType,
				threshold: +avaMenuMobileBreakpoint
			} );

		},

	};

	avaMenu.init();

}( jQuery ));
