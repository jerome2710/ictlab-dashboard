/**
 * @section : Global JavaScript functions / helpers
 * @project : ICT Lab Dashboard
 * @author  : Jerome Anker <0864155@hr.nl>
 */

(function (window, document, $, app) {

	'use strict';

	app.dev = false;

	if (window.location.href.indexOf('app_dev') !== -1) {
		app.dev = true;
	}

	/**
	 * Useful environment variable to see if we're dealing with a touch device
	 *
	 * @author Boye Oomens <boye@e-sites.nl>
	 * @type {Boolean}
	 */
	app.isTouchDevice = (function () {
		var msGesture = window.navigator && window.navigator.msMaxTouchPoints && window.MSGesture,
			touch = (( 'ontouchstart' in window ) || msGesture || window.DocumentTouch && document instanceof DocumentTouch);

		return !!touch;
	}());

	/**
	 * Generic helper and utility methods
	 * Do not use for site-specific logic
	 *
	 * @type {Object}
	 */
	app.util = {
		/**
		 * Kickstarts form validation based on Parsley.js
		 *
		 * @author Boye Oomens <boye@e-sites.nl>
		 * @param  {String} selector target elements
		 * @param  {Object} conf optional config object
		 * @see    http://parsleyjs.org/
		 */
		initFormValidation: function (selector, conf) {
			var $forms = $(selector);

			// Fail silenty when there are no forms in the DOM
			if ( !$forms.length ) {
				return;
			}

			// Instantiate Parsley plugin (no chaining after this)
			$forms.parsley($.extend({
				focus: 'none'
			}, conf || {}));

			// Apply main listener that display the corresponding error container
			$forms.each(function () {
				$(this).parsley().on('form:validated', app.util.scrollToErrorField);
			});
		},

		/**
		 * Helper function as alias for getElementById, mainly used to see if a certain DOM element exists
		 *
		 * @author Boye Oomens <boye@e-sites.nl>
		 * @param {String} id - id selector without the hash character
		 * @return {Boolean}
		 */
		isset: function (id) {
			return !!document.getElementById(id);
		},

		/**
		 * Check if global submit needs to be fixed...
		 */
		globalSubmit: function () {
			var active = false,
				$globalSubmit = $('.global-submit').eq(0);

			$('input, select').on('keyup change', function () {
				active = true;
			});

			$(window).perfscroll(function () {
				if ( active ) {
					if ( ( $(window).scrollTop() + $(window).height() - $globalSubmit.outerHeight() ) < $('.js-global-submit').eq(0).offset().top ) {
						$('body').addClass('fixed-global-submit');
					} else {
						$('body').removeClass('fixed-global-submit');
					}
				}
			}, 100);
		},

        /**
		 * Toggle the sidebar menu
         */
		toggleMenu: function () {
			$("#wrapper").toggleClass('show');
		}
	};

}(window, document, jQuery, app));