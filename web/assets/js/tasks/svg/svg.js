/**
 * Does SVG stuff :)
 *
 * @author   Iain van der Wiel <iain@e-sites.nl>
 * @version  0.1.0
 */

gulp.task('clean:svg', function () {
	var del = require('del');

	del([
		conf.path.svg + '/dist/'
	]);
});

gulp.task('svgconcat', ['clean:svg'], function () {
	var svgstore = require('gulp-svgstore');

	return gulp.src(conf.path.svg + '/src/*.svg')
		.pipe(handleError('svgconcat', 'SVG concatenation failed'))
		.pipe(svgstore({fileName: 'dist.svg'}))
		.pipe(gulp.dest(conf.path.svg + '/dist/'))
		.pipe(handleSuccess('svgconcat', 'SVG concatenation succeeded'));
});

gulp.task('replaceSketch', ['svgconcat'], function() {
	var replace = require('gulp-replace');

	return gulp.src([conf.path.svg + '/dist/src.svg'])
		.pipe(handleError('replaceSketch', 'SVG cleanup failed'))
		.pipe(replace(/(sketch:type=".+?)"/g, ''))
		.pipe(replace(/(sketch:name=".+?)"/g, ''))
		.pipe(replace(/<use xlink:href="#[^"]+"\/>/g, ''))
		.pipe(replace(/xlink:href="#[^"]+"/g, ''))
		//.pipe(replace(/fill="[^"]+"/g, ''))
		.pipe(gulp.dest(conf.path.svg + '/dist/'))
		.pipe(handleSuccess('svgconcat', 'SVG cleanup succeeded'));
});

gulp.task('svg', ['clean:svg', 'svgconcat', 'replaceSketch']);

tasker.addTask('default', 'svg');
tasker.addTask('deploy', 'svg');
tasker.addTask('watch', 'svg', conf.path.svg + '/src');