(function( $ ) {
	'use strict';

	$(function() {

		/* ==============================================
		   Live Preview
		   ============================================== */
		var $preview     = $( '#scopenine-live-preview' );
		var $previewText = $( '#scopenine-preview-content' );
		var $previewClose= $( '#scopenine-preview-close' );
		var hasPreview   = $preview.length > 0;

		function updatePreview() {
			if ( ! hasPreview ) return;

			var bgColor      = $( '#scopenine_background_color' ).val() || '#ff0000';
			var txtColor     = $( '#scopenine_text_color' ).val() || '#ffffff';
			var fontSize     = $( '#scopenine_font_size' ).val() || 14;
			var paddingTop   = $( '#scopenine_padding_top' ).val() || 10;
			var paddingRight = $( '#scopenine_padding_right' ).val() || 40;
			var paddingBottom= $( '#scopenine_padding_bottom' ).val() || 10;
			var paddingLeft  = $( '#scopenine_padding_left' ).val() || 40;

			$preview.css({
				'background-color': bgColor,
				'color': txtColor,
				'font-size': fontSize + 'px',
				'padding': paddingTop + 'px ' + paddingRight + 'px ' + paddingBottom + 'px ' + paddingLeft + 'px'
			});

			// Update close icon SVG fill color
			$previewClose.find( 'svg path' ).attr( 'fill', txtColor );
			// Also update close icon img filter if custom icon
			$previewClose.find( 'img' ).css( 'filter', '' );

			// Update links in preview
			$preview.find( 'a' ).css( 'color', txtColor );
		}

		// Sync color swatch <-> text input pairs, then update preview
		$( '.scopenine-color-field' ).each( function() {
			var $swatch = $( this ).find( '.scopenine-color-swatch' );
			var $text   = $( this ).find( '.scopenine-color-text' );

			// Swatch changed -> update text
			$swatch.on( 'input', function() {
				$text.val( $( this ).val() ).trigger( 'change' );
			});

			// Text typed/pasted -> update swatch
			$text.on( 'input change', function() {
				var val = $( this ).val();
				if ( /^#[0-9A-Fa-f]{6}$/.test( val ) ) {
					$swatch.val( val );
				}
			});
		});

		$( '#scopenine_background_color, #scopenine_text_color' ).on( 'input change', updatePreview );

		// Bind range sliders
		$( '#scopenine_font_size' ).on( 'input', function() {
			$( '#scopenine-font-size-value' ).text( $( this ).val() + 'px' );
			updatePreview();
		});

		/* ==============================================
		   Padding Inputs & Link Toggle
		   ============================================== */
		var $paddingInputs = $( '#scopenine_padding_top, #scopenine_padding_right, #scopenine_padding_bottom, #scopenine_padding_left' );
		var $paddingLink   = $( '#scopenine-padding-link' );
		var isPaddingLinked = false;

		// Toggle linked state
		$paddingLink.on( 'click', function() {
			isPaddingLinked = ! isPaddingLinked;
			$( this ).toggleClass( 'scopenine-linked', isPaddingLinked );

			// When linking, sync all values to the first (top)
			if ( isPaddingLinked ) {
				var syncVal = $( '#scopenine_padding_top' ).val();
				$paddingInputs.val( syncVal );
				updatePreview();
			}
		});

		// Update linked values on input
		$paddingInputs.on( 'input', function() {
			if ( isPaddingLinked ) {
				var newVal = $( this ).val();
				$paddingInputs.val( newVal );
			}
			updatePreview();
		});

		// Bind WYSIWYG content changes (TinyMCE)
		function bindTinyMCE() {
			if ( typeof tinyMCE !== 'undefined' && tinyMCE.get( 'scopenine_news_title' ) ) {
				tinyMCE.get( 'scopenine_news_title' ).on( 'keyup change input NodeChange', function() {
					if ( hasPreview ) {
						$previewText.html( this.getContent() );
					}
				});
			}
		}

		// TinyMCE may init late, retry a few times
		var tmceRetries = 0;
		var tmceInterval = setInterval( function() {
			tmceRetries++;
			if ( typeof tinyMCE !== 'undefined' && tinyMCE.get( 'scopenine_news_title' ) ) {
				bindTinyMCE();
				clearInterval( tmceInterval );
			}
			if ( tmceRetries > 20 ) clearInterval( tmceInterval );
		}, 250 );

		// Also bind the raw textarea (Code view)
		$( '#scopenine_news_title' ).on( 'keyup input', function() {
			if ( hasPreview ) {
				$previewText.html( $( this ).val() );
			}
		});

		/* ==============================================
		   Fancy Select Component
		   ============================================== */
		$( document ).on( 'click', '.scopenine-fancy-select-trigger', function( e ) {
			e.preventDefault();
			e.stopPropagation();

			var $select = $( this ).closest( '.scopenine-fancy-select' );
			var isOpen  = $select.hasClass( 'open' );

			// Close all other selects
			$( '.scopenine-fancy-select.open' ).not( $select ).removeClass( 'open' );

			// Toggle current
			$select.toggleClass( 'open', ! isOpen );
		});

		$( document ).on( 'click', '.scopenine-fancy-select-option', function() {
			var $option = $( this );
			var $select = $option.closest( '.scopenine-fancy-select' );
			var value   = $option.data( 'value' );
			var label   = $option.text().trim();
			var icon    = $option.data( 'icon' ) || $option.find( '.dashicons' ).attr( 'class' ).replace( 'dashicons ', '' );

			// Update hidden input
			$select.find( 'input[type="hidden"]' ).val( value ).trigger( 'change' );

			// Update trigger label and icon
			$select.find( '.scopenine-fancy-select-label' ).text( label );
			if ( icon ) {
				$select.find( '.scopenine-fancy-select-icon .dashicons' ).attr( 'class', 'dashicons ' + icon );
			}

			// Update selected state
			$select.find( '.scopenine-fancy-select-option' ).removeClass( 'selected' );
			$option.addClass( 'selected' );

			// Close dropdown
			$select.removeClass( 'open' );
		});

		// Close on outside click
		$( document ).on( 'click', function( e ) {
			if ( ! $( e.target ).closest( '.scopenine-fancy-select' ).length ) {
				$( '.scopenine-fancy-select.open' ).removeClass( 'open' );
			}
		});

		// Close on escape
		$( document ).on( 'keydown', function( e ) {
			if ( e.key === 'Escape' ) {
				$( '.scopenine-fancy-select.open' ).removeClass( 'open' );
			}
		});

		/* ==============================================
		   Display Mode — Page Selector Toggle
		   ============================================== */
		var $displayMode   = $( '#scopenine-display-mode-select' );
		var $pagesSelector = $( '#scopenine-pages-selector-wrap' );

		function togglePageSelector() {
			if ( $displayMode.val() === 'specific_pages' ) {
				$pagesSelector.removeClass( 'scopenine-hide' ).slideDown( 200 );
			} else {
				$pagesSelector.addClass( 'scopenine-hide' ).slideUp( 200 );
			}
		}

		$displayMode.on( 'change', togglePageSelector );

		// Initial toggle check
		togglePageSelector();

		/* ==============================================
		   Page Selector — Search / Filter
		   ============================================== */
		$( '#scopenine-pages-search' ).on( 'keyup', function() {
			var query = $( this ).val().toLowerCase();

			$( '.scopenine-page-item' ).each( function() {
				var title = $( this ).data( 'title' );
				if ( title.indexOf( query ) !== -1 ) {
					$( this ).show();
				} else {
					$( this ).hide();
				}
			});

			$( '.scopenine-pages-group-title' ).each( function() {
				var $title = $( this );
				var hasVisible = $title.nextUntil( '.scopenine-pages-group-title', '.scopenine-page-item:visible' ).length > 0;
				$title.toggle( hasVisible );
			});
		});

		/* ==============================================
		   Page Selector — Select All / Deselect All
		   ============================================== */
		$( '#scopenine-select-all' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#scopenine-pages-list input[type="checkbox"]' ).prop( 'checked', true );
		});

		$( '#scopenine-deselect-all' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#scopenine-pages-list input[type="checkbox"]' ).prop( 'checked', false );
		});

		/* ==============================================
		   Meta Box — Toggle Expiry Fields
		   ============================================== */
		$( '#scopenine-expiry' ).on( 'change', function() {
			$( this ).closest( '.scopenine-meta-control' ).next( '.scopenine-meta-control' ).toggleClass( 'scopenine-hide' );
		});

		/* ==============================================
		   Close Icon — Media Library Upload
		   ============================================== */
		var scopenineMediaFrame;

		$( '#scopenine-upload-close-icon' ).on( 'click', function( e ) {
			e.preventDefault();

			if ( scopenineMediaFrame ) {
				scopenineMediaFrame.open();
				return;
			}

			scopenineMediaFrame = wp.media({
				title: 'Select Close Icon',
				button: { text: 'Use this icon' },
				multiple: false,
				library: { type: [ 'image/svg+xml', 'image/png', 'image/gif', 'image/jpeg' ] }
			});

			scopenineMediaFrame.on( 'select', function() {
				var attachment = scopenineMediaFrame.state().get( 'selection' ).first().toJSON();
				$( '#scopenine_close_icon_url' ).val( attachment.url );
				$( '#scopenine-close-icon-img' ).attr( 'src', attachment.url );
				$( '#scopenine-reset-close-icon' ).show();

				// Update preview close icon
				if ( hasPreview ) {
					$previewClose.html( '<img src="' + attachment.url + '" alt="close" style="width:14px;height:14px;" />' );
				}
			});

			scopenineMediaFrame.open();
		});

		$( '#scopenine-reset-close-icon' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#scopenine_close_icon_url' ).val( '' );
			var defaultSrc = $( '#scopenine-close-icon-img' ).closest( '.scopenine-close-icon-field' ).data( 'default' ) || $( '#scopenine-close-icon-img' ).attr( 'src' );
			$( '#scopenine-close-icon-img' ).attr( 'src', defaultSrc );
			$( this ).hide();

			// Reset preview close icon to default SVG
			if ( hasPreview ) {
				var txtColor = $( '#scopenine_text_color' ).val() || '#ffffff';
				$previewClose.html( '<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.6746 0.3254C19.2408-0.1085 18.5373-0.1085 18.1035 0.3254L10 8.4289 1.8965 0.3254C1.4626-0.1085 0.7592-0.1085 0.3254 0.3254-0.1085 0.7592-0.1085 1.4626 0.3254 1.8965L8.4289 10 0.3254 18.1035C-0.1085 18.5374-0.1085 19.2407 0.3254 19.6746 0.7593 20.1085 1.4627 20.1085 1.8965 19.6746L10 11.5711 18.1035 19.6746C18.5373 20.1085 19.2408 20.1085 19.6746 19.6746 20.1084 19.2407 20.1084 18.5374 19.6746 18.1036L11.571 10 19.6746 1.8965C20.1084 1.4626 20.1084 0.7592 19.6746 0.3254Z" fill="' + txtColor + '"/></svg>' );
			}
		});

	});

})( jQuery );
