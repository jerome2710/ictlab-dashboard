/**
 * Global Parsley configuration
 *
 * @see  http://parsleyjs.org/doc/annotated-source/defaults.html
 * @type {Object}
 */
window.ParsleyConfig = {
	errorsWrapper: '<div class="parsley-error-line"></div>',
	errorTemplate: '<span></span>',
	i18n: {
		nl: {
			defaultMessage: 'Dit veld is niet correct ingevuld',
			type: {
				email: 'Dit lijkt geen geldig e-mail adres te zijn.',
				url: 'Dit lijkt geen geldige URL te zijn.',
				number: 'Deze waarde moet een nummer zijn.',
				integer: 'Deze waarde moet een nummer zijn.',
				digits: 'Deze waarde moet numeriek zijn.',
				alphanum: 'Deze waarde moet alfanumeriek zijn.'
			},
			notblank: 'Deze waarde mag niet leeg zijn.',
			required: 'Dit veld is verplicht.',
			pattern: 'Deze waarde lijkt onjuist te zijn.',
			min: 'Deze waarde mag niet lager zijn dan %s.',
			max: 'Deze waarde mag niet groter zijn dan %s.',
			range: 'Deze waarde moet tussen %s en %s liggen.',
			minlength: 'Deze tekst is te kort. Deze moet uit minimaal %s karakters bestaan.',
			maxlength: 'Deze waarde is te lang. Deze mag maximaal %s karakters lang zijn.',
			length:  'Deze waarde moet tussen %s en %s karakters lang zijn.',
			equalto: 'Deze waardes moeten identiek zijn.'
		}
	}
};