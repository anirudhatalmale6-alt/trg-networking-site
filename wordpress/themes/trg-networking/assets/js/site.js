/**
 * Header behaviour: desktop dropdowns, the mobile drawer and the sticky-header
 * shadow. Plain DOM, no framework, no jQuery — it is a few hundred bytes and
 * has nothing to hydrate.
 *
 * Everything degrades safely without JavaScript: the dropdown parents are real
 * links in the menu, so "Services" and "Industries" still reach their landing
 * pages, and the footer carries every child link as plain markup.
 */
(function () {
	'use strict';

	/* --------------------------------------------------------- dropdowns */

	var nav = document.querySelector('[data-trg-nav]');

	function closeAllDropdowns() {
		if (!nav) return;
		nav.querySelectorAll('[data-trg-dropdown-toggle]').forEach(function (btn) {
			btn.setAttribute('aria-expanded', 'false');
			var chevron = btn.querySelector('svg');
			if (chevron) chevron.classList.remove('rotate-180');
		});
		nav.querySelectorAll('[data-trg-dropdown-panel]').forEach(function (panel) {
			panel.classList.add('hidden');
		});
	}

	if (nav) {
		nav.querySelectorAll('[data-trg-dropdown-toggle]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var panel = document.getElementById(btn.getAttribute('aria-controls'));
				if (!panel) return;

				var isOpen = btn.getAttribute('aria-expanded') === 'true';
				closeAllDropdowns();

				if (!isOpen) {
					btn.setAttribute('aria-expanded', 'true');
					panel.classList.remove('hidden');
					var chevron = btn.querySelector('svg');
					if (chevron) chevron.classList.add('rotate-180');
				}
			});
		});

		// A dropdown left open after the pointer moves away is a trap on touch
		// devices, so close on any click outside the nav.
		document.addEventListener('mousedown', function (e) {
			if (!nav.contains(e.target)) closeAllDropdowns();
		});
	}

	/* ------------------------------------------------------------ drawer */

	var drawer = document.querySelector('[data-trg-drawer]');
	var openBtn = document.querySelector('[data-trg-drawer-open]');

	function setDrawer(open) {
		if (!drawer) return;
		drawer.classList.toggle('hidden', !open);
		document.body.style.overflow = open ? 'hidden' : '';
		if (openBtn) openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	if (openBtn) openBtn.addEventListener('click', function () { setDrawer(true); });

	document.querySelectorAll('[data-trg-drawer-close]').forEach(function (el) {
		el.addEventListener('click', function () { setDrawer(false); });
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeAllDropdowns();
			setDrawer(false);
		}
	});

	/* --------------------------------------------------- sticky-header shadow */

	var header = document.querySelector('[data-trg-header]');
	if (header) {
		var onScroll = function () {
			var scrolled = window.scrollY > 8;
			header.classList.toggle('shadow-[0_1px_0_0_#E2E8F0,0_8px_24px_-16px_rgba(15,23,42,0.25)]', scrolled);
			header.classList.toggle('border-b', !scrolled);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* ------------------------------------------------------ FAQ accordion */

	// The rows are native <details>, so they already open without this. All the
	// script adds is closing the others, and swapping the plus for a minus.
	var faqGroups = document.querySelectorAll('[data-trg-faq]');
	faqGroups.forEach(function (group) {
		var rows = group.querySelectorAll('details');
		rows.forEach(function (row) {
			row.addEventListener('toggle', function () {
				if (row.open) {
					rows.forEach(function (other) {
						if (other !== row) other.open = false;
					});
				}
			});
		});
	});
})();
