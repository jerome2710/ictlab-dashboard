/**
 * @section : Unobtrusive JavaScript events and function calls, triggered on DOMContentLoaded.
 * @project : ICT Lab Dashboard
 * @author  : Jerome Anker <0864155@hr.nl>
 */

(function (window, document, $, app) {

	'use strict';

	/**
	 * Main init function that kickstarts all site logic when the DOM is loaded
	 * Make sure all event handlers are placed within this function
	 *
	 * @private
	 */
	function _init() {

		// First, set Parsley locale
		window.Parsley.setLocale($('html').attr('lang'));

		// Parsley based form validation
		app.util.initFormValidation('.js-validate-form');

		// Resize graphical overlay
        app.util.resizeGraphicalOverlay();

		// Initialize JSChart
		app.util.initializeChart();

		// Initialize datepickers
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            endDate: '0d'
        });

        // Toggle menu
        $('.js-menu-toggle').on('click', function(e) {
            e.preventDefault();
            app.util.toggleMenu();
        });

        // Update sensor types dropdown
        app.util.initalizeSensorClicks();

        // Enable submit button
        $('.js-statistics-types').on('change', function () {
            $('.js-statistics-submit').removeClass('disabled');
        });

        // Load sensor data
        $('.js-statistics-submit').on('click', function (e) {
            e.preventDefault();
            app.util.updateChart();
        });

        // Add another sensor type to selection
        $('.js-statistics-addSensor').on('click', function(e) {
            e.preventDefault();
            app.util.addSensor(e);
        });

        // Resize dashboard graphical overlay
        $(window).on('resize', function() {
            app.util.resizeGraphicalOverlay();
        });
	}

	// Initialize
	$(document)
		.ready(_init);

}(window, document, jQuery, app));
