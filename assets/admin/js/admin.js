/**
 * ResizeSensor.js
 */
!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.ResizeSensor=t()}("undefined"!=typeof window?window:this,function(){function e(e,t){var i=Object.prototype.toString.call(e),n="[object Array]"===i||"[object NodeList]"===i||"[object HTMLCollection]"===i||"[object Object]"===i||"undefined"!=typeof jQuery&&e instanceof jQuery||"undefined"!=typeof Elements&&e instanceof Elements,o=0,s=e.length;if(n)for(;o<s;o++)t(e[o]);else t(e)}if("undefined"==typeof window)return null;var t=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||function(e){return window.setTimeout(e,20)},i=function(n,o){function s(){var e=[];this.add=function(t){e.push(t)};var t,i;this.call=function(){for(t=0,i=e.length;t<i;t++)e[t].call()},this.remove=function(n){var o=[];for(t=0,i=e.length;t<i;t++)e[t]!==n&&o.push(e[t]);e=o},this.length=function(){return e.length}}function r(e,i){if(e)if(e.resizedAttached)e.resizedAttached.add(i);else{e.resizedAttached=new s,e.resizedAttached.add(i),e.resizeSensor=document.createElement("div"),e.resizeSensor.className="resize-sensor";var n="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;",o="position: absolute; left: 0; top: 0; transition: 0s;";e.resizeSensor.style.cssText=n,e.resizeSensor.innerHTML='<div class="resize-sensor-expand" style="'+n+'"><div style="'+o+'"></div></div><div class="resize-sensor-shrink" style="'+n+'"><div style="'+o+' width: 200%; height: 200%"></div></div>',e.appendChild(e.resizeSensor),e.resizeSensor.offsetParent!==e&&(e.style.position="relative");var r,d,c,l,f=e.resizeSensor.childNodes[0],a=f.childNodes[0],h=e.resizeSensor.childNodes[1],u=e.offsetWidth,z=e.offsetHeight,v=function(){a.style.width="100000px",a.style.height="100000px",f.scrollLeft=1e5,f.scrollTop=1e5,h.scrollLeft=1e5,h.scrollTop=1e5};v();var p=function(){d=0,r&&(u=c,z=l,e.resizedAttached&&e.resizedAttached.call())},y=function(){c=e.offsetWidth,l=e.offsetHeight,(r=c!=u||l!=z)&&!d&&(d=t(p)),v()},m=function(e,t,i){e.attachEvent?e.attachEvent("on"+t,i):e.addEventListener(t,i)};m(f,"scroll",y),m(h,"scroll",y)}}e(n,function(e){r(e,o)}),this.detach=function(e){i.detach(n,e)}};return i.detach=function(t,i){e(t,function(e){e&&(e.resizedAttached&&"function"==typeof i&&(e.resizedAttached.remove(i),e.resizedAttached.length())||e.resizeSensor&&(e.contains(e.resizeSensor)&&e.removeChild(e.resizeSensor),delete e.resizeSensor,delete e.resizedAttached))})},i});

( function( $, settings ) {

	'use strict';

	var avaMenuAdmin = {

		instance: [],
		savedTimeout: null,
		menuId: 0,
		depth: 0,

		saveHandlerId: 'ava_menu_save_options_ajax',
		resetHandlerId: 'ava_menu_restore_options_ajax',

		saveOptionsHandlerInstance: null,
		resetOptionsHandlerInstance: null,

		init: function() {

			this.initTriggers();

			$( document )
				.on( 'click.avaMenuAdmin', '.ava-settings-tabs__nav-item ', this.switchTabs )
				.on( 'click.avaMenuAdmin', '.ava-menu-editor', this.openEditor )
				.on( 'click.avaMenuAdmin', '.ava-menu-trigger', this.initPopup )
				.on( 'click.avaMenuAdmin', '.ava-menu-popup__overlay', this.closePopup )
				.on( 'click.avaMenuAdmin', '.ava-close-frame', this.closeEditor )
				.on( 'click.avaMenuAdmin', '.ava-save-menu', this.saveMenu )
				.on( 'click.avaMenuAdmin', '.ava-menu-settins-save', this.saveSettins )
				.on( 'click.avaMenuAdmin', '.ava-menu-import-btn', this.switchImportControl )
				.on( 'click.avaMenuAdmin', '.ava-menu-run-import-btn', this.runImport )
				.on( 'click.avaMenuAdmin', '#ava-menu-reset-options', this.resetOptions )
				.on( 'click.avaMenuAdmin', '#ava-menu-create-preset', this.createPreset )
				.on( 'click.avaMenuAdmin', '#ava-menu-update-preset', this.updatePreset )
				.on( 'click.avaMenuAdmin', '#ava-menu-load-preset', this.loadPreset )
				.on( 'click.avaMenuAdmin', '#ava-menu-delete-preset', this.deletePreset )
				.on( 'click.avaMenuAdmin', '.ava-menu-popup__close', this.closePopup )
				.on( 'focus.avaMenuAdmin', '.ava-preset-name', this.clearPresetError );


			this.saveOptionsHandlerInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.saveHandlerId,
					successCallback: this.saveSuccessCallback.bind( this )
				}
			);
			this.resetOptionsHandlerInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.resetHandlerId,
					successCallback: this.restoreSuccessCallback.bind( this )
				}
			);

			this.addOptionPageEvents();

			if ( 0 < $( '.cherry-tab__tabs-wrap' ).length ) {

				var stickySidebar = new StickySidebar( '.cherry-tab__tabs-wrap', {
					topSpacing: 55,
					containerSelector: '.cherry-tab__tabs',
					innerWrapperSelector: '.cherry-tab__tabs-wrap-content'
				} );

			}

		},

		createPreset: function() {

			var $this      = $( this ),
				$input     = $this.prev( '.cherry-ui-text' ),
				$msg       = $this.next( '.ava-preset-msg' ),
				presetName = $input.val(),
				fields     = null,
				data       = {};

			if ( '' === presetName ) {
				$msg.addClass( 'ava-menu-error-message' ).text( settings.optionPageMessages.preset.nameError );
				return;
			}

			data.action   = 'ava_menu_create_preset';
			data.name     = presetName;
			data.settings = CherryJsCore.cherryHandlerUtils.serializeObject( $( '#ava-menu-options-form' ) );

			$this.prop( 'disabled', true );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: data
			}).done( function( response ) {
				if ( true === response.success ) {
					$msg.text( settings.optionPageMessages.preset.created );
					window.location = settings.menuPageUrl;
				} else {
					$this.prop( 'disabled', false );
					$msg.addClass( 'ava-menu-error-message' ).text( response.data.message );
				}

			});
		},

		updatePreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.ava-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.updateError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmUpdate ) ) {

				data.action   = 'ava_menu_update_preset';
				data.preset   = preset;
				data.settings = CherryJsCore.cherryHandlerUtils.serializeObject( $( '#ava-menu-options-form' ) );

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.updated );
					$this.prop( 'disabled', false );
					setTimeout( function() {
						$msg.empty();
					}, 3000 );
				});
			}

		},

		loadPreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.ava-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.loadError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmLoad ) ) {

				data.action   = 'ava_menu_load_preset';
				data.preset   = preset;

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.loaded );
					window.location = settings.menuPageUrl;
				});
			}

		},

		deletePreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.ava-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.deleteError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmDelete ) ) {

				data.action   = 'ava_menu_delete_preset';
				data.preset   = preset;

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.deleted );
					window.location = settings.menuPageUrl;
				});
			}

		},

		clearPresetError: function() {
			$( this ).siblings( '.ava-preset-msg' ).removeClass( 'ava-menu-error-message' ).text( '' );
		},

		resetOptions: function() {

			if ( confirm( settings.optionPageMessages.resetMessage ) ) {
				window.location = settings.resetUrl;
			}

		},

		switchImportControl: function() {
			$( this ).siblings( '.ava-menu-import' ).toggleClass( 'import-active' );
		},

		runImport: function() {

			var $this      = $( this ),
				$fileInput = $this.siblings( '.ava-menu-import-file' ),
				$messages  = $this.siblings( '.ava-menu-import-messages' ),
				file       = $fileInput.val();

			$messages.removeClass( 'ava-menu-error-message ava-menu-success-message' ).html( '' );

			if ( ! file ) {
				$messages.addClass( 'ava-menu-error-message' ).html( settings.optionPageMessages.emptyImportFile );
				return;
			}

			var fileExt = file.split('.').pop().toLowerCase();

			if ( 'json' !== fileExt ) {
				$messages.addClass( 'ava-menu-error-message' ).html( settings.optionPageMessages.incorrectImportFile );
				return;
			}

			var fileToLoad = $fileInput[0].files[0];
			var fileReader = new FileReader();

			$this.prop( 'disabled', true );

			fileReader.onload = function( fileLoadedEvent ) {

				var textFromFileLoaded = fileLoadedEvent.target.result;

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'ava_menu_import_options',
						data: JSON.parse( textFromFileLoaded )
					},
				}).done( function( response ) {

					if ( true === response.success ) {
						$messages.addClass( 'ava-menu-success-message' ).html( response.data.message );
						window.location.reload();
					} else {
						$messages.addClass( 'ava-menu-error-message' ).html( response.data.message );
					}

					$this.prop( 'disabled', false );

				});

			};

			fileReader.readAsText( fileToLoad, 'UTF-8' );

		},

		openEditor: function() {

			var $popup   = $( this ).closest( '.ava-menu-popup' ),
				menuItem = $popup.attr( 'data-id' ),
				url      = settings.editURL.replace( '%id%', menuItem ),
				frame    = null,
				loader   = null,
				editor   = wp.template( 'editor-frame' );

			url = url.replace( '%menuid%', settings.currentMenuId );

			$popup
				.addClass( 'ava-menu-editor-active' )
				.find( '.ava-menu-editor-wrap' )
				.addClass( 'ava-editor-active' )
				.html( editor( { url: url } ) );

			frame  = $popup.find( '.ava-edit-frame' )[0];
			loader = $popup.find( '#elementor-loading' );

			$( frame.contentWindow ).load( function() {
				$popup.find( '.ava-close-frame' ).addClass( 'ava-loaded' );
				loader.fadeOut( 300 );
			} );

		},

		initPopup: function() {

			var $this   = $( this ),
				id      = $this.data( 'item-id' ),
				depth   = $this.data( 'item-depth' ),
				content = null,
				wrapper = wp.template( 'popup-wrapper' ),
				tabs    = wp.template( 'popup-tabs' );

			if ( ! avaMenuAdmin.instance[ id ] ) {

				content = wrapper( {
					id: id,
					content: tabs( { tabs: settings.tabs, depth: depth } ),
					saveLabel: settings.strings.saveLabel
				} );

				$( 'body' ).append( content );
				avaMenuAdmin.instance[ id ] = '#ava-popup-' + id;
			}

			$( avaMenuAdmin.instance[ id ] ).removeClass( 'ava-hidden' );

			avaMenuAdmin.menuId = id;
			avaMenuAdmin.depth  = depth;

			avaMenuAdmin.tabs.showActive(
				$( avaMenuAdmin.instance[ id ] ).find( '.ava-settings-tabs__nav-item:first' )
			);

		},

		switchTabs: function() {
			avaMenuAdmin.tabs.showActive( $( this ) );
		},

		saveSettins: function() {

			var $this        = $( this ),
				$loader      = $this.closest( '.submit' ).siblings( '.spinner' ),
				$saved       = $this.closest( '.submit' ).siblings( '.dashicons-yes' ),
				data         = [],
				preparedData = {};

			data = $( '.ava-menu-settings-fields input, .ava-menu-settings-fields select' ).serializeArray();

			$.each( data, function( index, field ) {
				preparedData[ field.name ] = field.value;
			});

			clearTimeout( avaMenuAdmin.savedTimeout );

			$saved.addClass( 'hidden' );
			$loader.css( 'visibility', 'visible' );

			preparedData.action       = 'ava_save_settings';
			preparedData.current_menu = settings.currentMenuId;

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: preparedData
			}).done( function( response ) {

				$loader.css( 'visibility', 'hidden' );

				if ( true === response.success ) {
					$saved.removeClass( 'hidden' );
					avaMenuAdmin.savedTimeout = setTimeout( function() {
						$saved.addClass( 'hidden' );
					}, 1000 );
				}
			});

		},

		saveMenu: function() {

			var $this        = $( this ),
				$loader      = $this.siblings( '.spinner' ),
				$saved       = $this.siblings( '.dashicons-yes' ),
				data         = [],
				preparedData = {};

			data = $( '.ava-settings-tabs__content input, .ava-settings-tabs__content select' ).serializeArray();

			$.each( data, function( index, field ) {
				preparedData[ field.name ] = field.value;
			});

			clearTimeout( avaMenuAdmin.savedTimeout );

			$saved.addClass( 'hidden' );
			$loader.css( 'visibility', 'visible' );

			preparedData.action  = 'ava_save_menu';
			preparedData.menu_id = avaMenuAdmin.menuId;

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: preparedData
			}).done( function( response ) {

				$loader.css( 'visibility', 'hidden' );

				if ( true === response.success ) {
					$saved.removeClass( 'hidden' );
					avaMenuAdmin.savedTimeout = setTimeout( function() {
						$saved.addClass( 'hidden' );
					}, 1000 );
				}
			});

		},

		tabs: {
			showActive: function( $item ) {

				var tab      = $item.data( 'tab' ),
					action   = $item.data( 'action' ),
					template = $item.data( 'template' ),
					$content = $item.closest( '.ava-settings-tabs' ).find( '.ava-settings-tabs__content-item[data-tab="' + tab + '"]' ),
					loaded   = parseInt( $content.data( 'loaded' ) );

				if ( $item.hasClass( 'ava-active-tab' ) ) {
					return;
				}

				if ( 0 === loaded ) {
					avaMenuAdmin.tabs.loadTabContent( tab, $content, template, action );
				}

				$item.addClass( 'ava-active-tab' ).siblings().removeClass( 'ava-active-tab' );
				$content.removeClass( 'ava-hidden-tab' ).siblings().addClass( 'ava-hidden-tab' );

			},

			loadTabContent: function( tab, $content, template, action ) {

				if ( ! template && ! action ) {
					return;
				}

				var renderTemplate = null,
					$popup         = $content.closest( '.ava-menu-popup' ),
					id             = $popup.attr( 'data-id' ),
					data           = {};

				$content.has( '.tab-loader' ).addClass( 'tab-loading' );

				if ( ! template ) {

					if ( 0 < settings.tabs[ tab ].data.length ) {
						data         = settings.tabs[ tab ].data;
						data.action  = action;
						data.menu_id = id;
					} else {
						data = {
							action: action,
							menu_id: id
						};
					}

					$.ajax({
						url: ajaxurl,
						type: 'get',
						dataType: 'json',
						data: data
					}).done( function( response ) {
						if ( true === response.success ) {

							$content.removeClass( 'tab-loading' ).html( response.data.content );

							if ( CherryJsCore.ui_elements.iconpicker && window.cherry5IconSets ) {
								CherryJsCore.ui_elements.iconpicker.setIconsSets( window.cherry5IconSets );
							}

							$( 'body' ).trigger( {
								type: 'cherry-ui-elements-init',
								_target: $content
							} );

						}
					});

				} else {
					renderTemplate = wp.template( template );
					$content.html( renderTemplate( settings.tabs[ tab ].data ) );
				}

				$content.data( 'loaded', 1 );

			}
		},

		closePopup: function( event ) {

			event.preventDefault();

			avaMenuAdmin.menuId = 0;
			avaMenuAdmin.depth  = 0;

			$( this )
				.closest( '.ava-menu-popup' ).addClass( 'ava-hidden' )
				.removeClass( 'ava-menu-editor-active' )
				.find( '.ava-menu-editor-wrap.ava-editor-active' ).removeClass( 'ava-editor-active' )
				.find( '.ava-close-frame' ).removeClass( 'ava-loaded' )
				.siblings( '#elementor-loading' ).fadeIn( 0 );
		},

		closeEditor: function( event ) {

			var $this    = $( this ),
				$popup   = $this.closest( '.ava-menu-popup' ),
				$frame   = $( this ).siblings( 'iframe' ),
				$loader  = $popup.find( '#elementor-loading' ),
				$editor  = $frame.closest( '.ava-menu-editor-wrap' ),
				$content = $frame[0].contentWindow,
				saver    = null,
				enabled  = true;

			if ( $content.elementor.saver && 'function' === typeof $content.elementor.saver.isEditorChanged ) {
				saver = $content.elementor.saver;
			} else if ( 'function' === typeof $content.elementor.isEditorChanged ) {
				saver = $content.elementor;
			}

			if ( null !== saver &&  true === saver.isEditorChanged() ) {

				if ( ! confirm( settings.strings.leaveEditor ) ) {
					enabled = false;
				}

			}

			if ( ! enabled ) {
				return;
			}

			$loader.fadeIn(0);
			$popup.removeClass( 'ava-menu-editor-active' );
			$this.removeClass( 'ava-loaded' );
			$editor.removeClass( 'ava-editor-active' );

		},

		getItemId: function( $item ) {
			var id = $item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},


		getItemDepth: function( $item ) {
			var depthClass = $item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			}

			return depthClass[0].replace( 'menu-item-depth-', '' );
		},

		initTriggers: function() {

			var trigger = wp.template( 'menu-trigger' );

			$( document ).on( 'menu-item-added', function( event, $menuItem ) {
				var id = avaMenuAdmin.getItemId( $menuItem );
				$menuItem.find( '.item-title' ).append( trigger( { id: id, label: settings.strings.triggerLabel } ) );
			});

			$( '#menu-to-edit .menu-item' ).each( function() {
				var $this = $( this ),
					depth = avaMenuAdmin.getItemDepth( $this ),
					id    = avaMenuAdmin.getItemId( $this );

				$this.find( '.item-title' ).append( trigger( {
					id: id,
					depth: depth,
					label: settings.strings.triggerLabel
				} ) );
			});

		},

		addOptionPageEvents: function() {
			$( 'body' )
				.on( 'click', '#ava-menu-save-options', this.saveOptionsHandler.bind( this ) )
				.on( 'click', '#ava-menu-restore-options', this.resetOptionsHandler.bind( this ) );
		},

		saveOptionsHandler: function( event ) {
			this.disableFormButton( event.target );
			this.saveOptionsHandlerInstance.sendFormData( '#ava-menu-options-form' );
		},

		resetOptionsHandler: function( event ) {
			this.disableFormButton( event.target );
			this.resetOptionsHandlerInstance.send();
		},

		saveSuccessCallback: function() {
			this.enableFormButton( '#ava-menu-save-options' );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.avaMenuAdminSettings.optionPageMessages.saveMessage );
		},

		restoreSuccessCallback: function() {
			this.enableFormButton( '#ava-menu-restore-options' );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.avaMenuAdminSettings.optionPageMessages.restoreMessage );

			setTimeout( function() {
				window.location.href = window.avaMenuAdminSettings.optionPageMessages.redirectUrl;
			}, 500 );
		},

		disableFormButton: function( button ) {
			$( button )
				.attr( 'disabled', 'disabled' );
		},

		enableFormButton: function( button ) {
			var timer = null;

			$( button )
				.removeAttr( 'disabled' )
				.addClass( 'success' );

			timer = setTimeout(
				function() {
					$( button ).removeClass( 'success' );
					clearTimeout( timer );
				},
				1000
			);
		}

	};

	avaMenuAdmin.init();

}( jQuery, window.avaMenuAdminSettings ) );
