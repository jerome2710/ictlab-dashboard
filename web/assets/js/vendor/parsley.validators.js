window.Parsley.addValidator('fileSizeMax', {
	requirementType: ['integer', 'string'],
	validateString: function validateString(value, maxSize, sizeMultiplyer, parsleyFieldInstance) {
		sizeMultiplyer = sizeMultiplyer.toLowerCase();

		var files = parsleyFieldInstance.$element[0].files;
		var filesSizes = {
			b: 1,
			kb: 1024,
			mb: 1024 * 1024,
			gb: 1024 * 1024 * 1024
		};

		// Multiply the max file size
		maxSize = maxSize * filesSizes[sizeMultiplyer.toLowerCase()];

		// If a file is present in the input
		if (files.length > 0) {
			// Loop over the files
			for (var i = 0; i < files.length; i++) {
				console.log(files[i].size);
				if (files[i].size > maxSize) {
					return false;
				}
			}
		}

		return true;
	},
	messages: {
		nl: 'Het gekozen bestand is te groot.'
	}
});