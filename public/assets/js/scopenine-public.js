/**
 * Scope Nine Alert Bar - front-end behaviour.
 *
 * Handles the dismiss button and, for the "after-header" position,
 * moves the bar directly below the theme header.
 *
 * @package Scope_Nine_Alert_Bar
 */
( function () {
	'use strict';

	/**
	 * Scrolling text: keep a steady reading speed (about 70px per second)
	 * whatever the message length, and toggle pause on tap for touch screens.
	 */
	function setupScroll( bar ) {
		var track = bar.querySelector( '.scopenine-alert-bar-track' );
		if ( ! track || ! /scopenine-scroll-(mobile|always)/.test( bar.className ) ) {
			return;
		}
		function setDuration() {
			var distance = track.offsetWidth;
			if ( distance > 0 ) {
				bar.style.setProperty( '--scopenine-scroll-duration', Math.max( 6, distance / 70 ) + 's' );
			}
		}
		setDuration();
		window.addEventListener( 'resize', setDuration );
		track.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a' ) ) {
				return;
			}
			bar.classList.toggle( 'scopenine-scroll-paused' );
		} );
	}

	function init() {
		var bar = document.getElementById( 'scopenine-alert-bar' );
		if ( ! bar ) {
			return;
		}

		setupScroll( bar );

		var closeBtn = bar.querySelector( '.scopenine-alert-bar-close' );
		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', function () {
				bar.style.transition = 'opacity 0.3s, max-height 0.3s';
				bar.style.opacity = '0';
				bar.style.maxHeight = '0';
				bar.style.overflow = 'hidden';
				bar.style.padding = '0';
				bar.style.minHeight = '0';
				setTimeout( function () {
					bar.remove();
				}, 350 );
			} );
		}

		if ( 'after-header' !== bar.getAttribute( 'data-scopenine-position' ) ) {
			return;
		}

		var header = document.querySelector( '.site-header' ) ||
			document.querySelector( '#site-header' ) ||
			document.querySelector( '#masthead' ) ||
			document.querySelector( 'header.wp-block-template-part' ) ||
			document.querySelector( '.wp-block-template-part header' ) ||
			document.querySelector( 'header' );

		if ( header && header.parentNode ) {
			header.parentNode.insertBefore( bar, header.nextSibling );
		}
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
