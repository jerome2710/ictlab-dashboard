/**
 * @section : Global JavaScript functions / helpers
 * @project : ICT Lab Dashboard
 * @author  : Jerome Anker <0864155@hr.nl>
 */

(function (window, document, $, app) {

	'use strict';

	app.dev = window.location.href.indexOf('app_dev') !== -1;
	app.chart = null;

	const STATUS_SUCCESS = 'success';

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
         * @param e
         */
        updateSensorTypes: function (e) {
        	var target = $(e.target),
        		data = target.find(':selected').data('types'),
        		typesElement = target.closest('.sensor').find('.js-statistics-types');

        	// fill select
            typesElement.empty();
            typesElement.append($('<option>').text('Choose a sensor type').attr('disabled', 'disabled').attr('selected', 'selected'));
            $.each(data, function (index, value) {
                typesElement.append($('<option>').text(value).attr('value', value));
            });
		},

        /**
		 * Add another sensor to selection
		 *
         * @param e
         */
        addSensor: function (e) {
            var html = $('#js-statistics-addSensor-template').html(),
                target = $(e.target),
                index = target.data('sensor-count') + 1,
                result = html.replace(/\%index%/g, index);

            $('.sensor:last').after(result);
            target.data('sensor-count', index);

            this.initalizeSensorClicks();
		},

        /**
		 * Initialize on click for removeSensor - only for last added sensor
         */
        initalizeSensorClicks: function () {
        	// Type updating
            $('.sensor:last .js-statistics-sensor').on('change', function(e) {
                e.preventDefault();
                app.util.updateSensorTypes(e);
            });

        	// Removing
            $('.sensor:last .js-statistics-removeSensor').one('click', function(e) {
                e.preventDefault();
                app.util.removeSensor(e);
            });
		},

        /**
		 * Remove sensor type from selection
		 *
         * @param e
         */
        removeSensor: function (e) {
			var addButton = $('.js-statistics-addSensor'),
				parent = $(e.target).closest('.sensor');

			parent.remove();
			addButton.data('sensor-count', addButton.data('sensor-count') - 1);
		},

        /**
		 * Initialize the chart
         */
		initializeChart: function (labels, dataset) {
            var chartElement = $('#readingsChart'),
				chartOverlay = $('.chart-overlay');

            console.log('Initializing chart');

            // chart not found
			if (chartElement.length === 0) {
				return;
			}

			// demo data
			if (dataset === undefined) {
				labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
				dataset = [{label: 'Fake chart', readings: [18.1, 19.2, 18.9, 19.3, 19.4, 19.0, 18.8]}];
			} else {
                chartOverlay.hide();
            }

            var datasets = [];
            for (var i = 0; i < dataset.length; i++) {
				datasets.push({
                    label: dataset[i].label,
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
                    data: dataset[i].readings,
                    spanGaps: false
                });
			}

			// build the chart
            app.chart = new Chart(chartElement, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
				},
                options: {
                    responsive: true,
                    maintainAspectRatio: false
				}
            });
		},

        /**
		 * Update the chart
         */
        updateChart: function () {
			var chartOverlay = $('.chart-overlay'),
				dateFrom = $('.js-statistics-dateFrom').val(),
				dateTo = $('.js-statistics-dateTo').val(),
				interval = $('.js-statistics-interval').find('.active input').val(),
				sensors = $('select[name^=sensors]'),
				sensorData = {};

			console.log('Updating chart');

			// show loading screen
			chartOverlay.show();
			chartOverlay.html('<p><i class="fa fa-spinner fa-spin"></i> Loading...</p>');

			// prepare sensor request data
			const regex = /sensors\[(\w+)]\[(\w+)]/;
			sensors.each(function () {
				var name = $(this).attr('name'),
					value = $(this).val(),
					groups = regex.exec(name),
					index = parseInt(groups[1]),
					key = groups[2];

				if (sensorData[index] === undefined) {
                    sensorData[index] = {
						sensor: '',
						type: ''
					}
				}

                sensorData[index][key] = value;
			});

			$.ajax({
				url: (app.dev && '/app_dev.php') + '/xhr/readings',
				method: 'GET',
				data: {
					dateFrom: dateFrom,
					dateTo: dateTo,
					interval: interval,
					sensors: sensorData
				}
			}).done(function (result) {
				if (result.status === STATUS_SUCCESS) {
                    app.util.initializeChart(result.data.labels, result.data.dataset);
				} else {
                    app.util.setChartFailMessage('Something went wrong, please try again.');
                }
			}).fail(function (jqXHR, textStatus) {
                app.util.setChartFailMessage('Something went wrong, please try again. ( ' + textStatus + ')');
            });
		},

        /**
		 * Set the chart overlay with fail message
		 *
         * @param message
         */
		setChartFailMessage: function (message) {
        	var overlay = $('.chart-overlay');

        	overlay.show().addClass('text-danger').html('<p>' + message + '</p>');
		}
	};

}(window, document, jQuery, app));