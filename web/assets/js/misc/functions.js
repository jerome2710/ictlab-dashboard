/**
 * @section : Global JavaScript functions / helpers
 * @project : ICT Lab Dashboard
 * @author  : Jerome Anker <0864155@hr.nl>
 */

(function (window, document, $, app) {

	'use strict';

	app.dev = window.location.href.indexOf('app_dev') !== -1;
	app.chart = null;

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
			$('#wrapper').toggleClass('show');
		},

        /**
		 * Resize the graphical overlay on the dashboard
         */
        resizeGraphicalOverlay: function() {
			var graphicalElement = $('.graphical-overlay');
			var ratio = 1.65;

			if (graphicalElement.length === 0) {
				return;
			}

			// calculate some widths, heights and paddings
            var calculatedRight = 30;
            var calculatedWidth = $(window).width() - $('#sidebar-toggle').width() - calculatedRight - 50;

            var calculatedTop = $('.content .header').height() + 20;
            var calculatedBottom = 100;
			var calculatedHeight = $(window).height() - calculatedTop - calculatedBottom;

			// force ratio
			var currentRatio = calculatedWidth / calculatedHeight;
			if (currentRatio !== ratio) {
				if (currentRatio > ratio) {
					// less width
					calculatedWidth = calculatedHeight * ratio;
				} else {
					// less height
					calculatedHeight = calculatedWidth / ratio;
				}
			}

			// resize
			graphicalElement.width(calculatedWidth);
			graphicalElement.height(calculatedHeight);
			graphicalElement.css('top', calculatedTop);
			graphicalElement.css('right', calculatedRight);
		},

        /**
		 * Update sensor types with given data
		 *
         * @param data
         */
        updateSensorTypes: function (data) {
        	var typesElement = $('.js-statistics-types');

        	// fill select
            typesElement.empty();
            typesElement.append($('<option>').text('Choose a sensor type').attr('disabled', 'disabled').attr('selected', 'selected'));
            $.each(data, function (index, value) {
                typesElement.append($('<option>').text(value).attr('value', index));
            });

            // enable button
			$('.js-statistics-button').removeClass('disabled');
		},

        /**
		 * Initialize the chart
		 *
         * @param selector
         */
		initializeChart: function (selector) {
            var chartElement = $(selector);

            // chart not found
			if (chartElement.length === 0) {
				return;
			}

            var data = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "My First dataset",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [65, 59, 80, 81, 56, 55, 40],
                        spanGaps: false,
                    },
                    {
                        type: 'bar',
                        label: 'Bar Component',
                        data: [100, 50, 30],
                    }
                ]
            };

            var options = {
                responsive: true,
				maintainAspectRatio: false
			};

            app.chart = new Chart(chartElement, {
                type: 'line',
                data: data,
                options: options
            });
		}
	};

}(window, document, jQuery, app));