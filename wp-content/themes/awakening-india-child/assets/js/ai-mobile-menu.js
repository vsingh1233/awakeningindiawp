/**
 * Mobile menu parity repair.
 *
 * Divi renders the mobile menu trigger as a non-focusable <span> with no ARIA state,
 * and renders submenus without the collapsible disclosure the React reference uses.
 * This restores both, scoped to the header menu module only.
 *
 * React reference: src/components/site/Header.tsx (mobile panel, lines ~180-262).
 */
( function () {
	'use strict';

	var SELECTOR_MENU = '.ai-site-header__menu';

	function upgradeTrigger( root ) {
		var bar = root.querySelector( '.mobile_menu_bar' );
		var nav = root.querySelector( '.mobile_nav' );
		var list = root.querySelector( '.et_mobile_menu' );
		if ( ! bar || ! nav || ! list || bar.dataset.aiEnhanced === 'true' ) {
			return;
		}
		bar.dataset.aiEnhanced = 'true';

		if ( ! list.id ) {
			list.id = 'ai-mobile-menu';
		}
		bar.setAttribute( 'role', 'button' );
		bar.setAttribute( 'tabindex', '0' );
		bar.setAttribute( 'aria-controls', list.id );
		bar.setAttribute( 'aria-expanded', nav.classList.contains( 'opened' ) ? 'true' : 'false' );
		bar.setAttribute( 'aria-label', 'Open menu' );

		// Divi toggles .opened on click; mirror that state onto ARIA.
		var observer = new MutationObserver( function () {
			var open = nav.classList.contains( 'opened' );
			bar.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			bar.setAttribute( 'aria-label', open ? 'Close menu' : 'Open menu' );
		} );
		observer.observe( nav, { attributes: true, attributeFilter: [ 'class' ] } );

		// The <span> is not natively keyboard operable.
		bar.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Enter' || event.key === ' ' || event.key === 'Spacebar' ) {
				event.preventDefault();
				bar.click();
			}
		} );
	}

	function upgradeSubmenus( root ) {
		var parents = root.querySelectorAll( '.et_mobile_menu > li.menu-item-has-children' );

		Array.prototype.forEach.call( parents, function ( item, index ) {
			var submenu = item.querySelector( ':scope > ul' );
			var link = item.querySelector( ':scope > a' );
			if ( ! submenu || ! link || item.querySelector( ':scope > .ai-submenu-toggle' ) ) {
				return;
			}

			if ( ! submenu.id ) {
				submenu.id = 'ai-submenu-' + index;
			}
			item.classList.add( 'ai-has-submenu' );
			submenu.hidden = true;

			var toggle = document.createElement( 'button' );
			toggle.type = 'button';
			toggle.className = 'ai-submenu-toggle';
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.setAttribute( 'aria-controls', submenu.id );
			toggle.setAttribute( 'aria-label', 'Show ' + link.textContent.trim() + ' pages' );

			toggle.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				event.stopPropagation();
				var open = toggle.getAttribute( 'aria-expanded' ) === 'true';
				toggle.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
				submenu.hidden = open;
				item.classList.toggle( 'ai-submenu-open', ! open );
			} );

			item.insertBefore( toggle, submenu );
		} );
	}

	var applying = false;

	function needsUpgrade( root ) {
		var bar = root.querySelector( '.mobile_menu_bar' );
		if ( ! bar ) {
			return false;
		}
		return bar.dataset.aiEnhanced !== 'true' ||
			root.querySelectorAll( '.et_mobile_menu > li.menu-item-has-children' ).length !==
			root.querySelectorAll( '.et_mobile_menu > li > .ai-submenu-toggle' ).length;
	}

	function init() {
		var root = document.querySelector( SELECTOR_MENU );
		if ( ! root || applying || ! needsUpgrade( root ) ) {
			return;
		}
		applying = true;
		upgradeTrigger( root );
		upgradeSubmenus( root );
		applying = false;
	}

	function start() {
		var root = document.querySelector( SELECTOR_MENU );
		if ( ! root ) {
			return;
		}
		init();
		// Divi's menu runtime re-renders the mobile list after page load, discarding
		// these enhancements, so reapply whenever that subtree is replaced.
		new MutationObserver( init ).observe( root, { childList: true, subtree: true } );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', start );
	} else {
		start();
	}
}() );
