/**
 * Gulp file.
 *
 * @author S.M. Rafiz <https://github.com/smrafiz>
 * @package Prefix\MyPluginBoilerplate
 * @version 1.0.0
 */

/**
 * Load custom gulp Configuration.
 *
 */
const config = require('./plugin.config.js');

/**
  * Load Plugins.
  *
  */
const gulp = require('gulp');

// CSS related plugins.
const sass         = require('gulp-sass')(require('sass'));
const minifyCss    = require('gulp-uglifycss');
const autoPrefixer = require('gulp-autoprefixer');
const mmq          = require('gulp-merge-media-queries');
const beautifyCss  = require('gulp-cssbeautify');

// JS related plugins.
const concat        = require('gulp-concat');
const webpack       = require('webpack');
const webpackStream = require('webpack-stream');
const webpackConfig = require('./webpack.config.js');

// Image related plugins.
const imagemin = require('gulp-imagemin');

// Utility related plugins.
const rename      = require('gulp-rename');
const lineec      = require('gulp-line-ending-corrector');
const filter      = require('gulp-filter');
const sourceMaps  = require('gulp-sourcemaps');
const notify      = require('gulp-notify');
const browserSync = require('browser-sync').create();
const wpPot       = require('gulp-wp-pot');
const sort        = require('gulp-sort');
const cache       = require('gulp-cache');
const remember    = require('gulp-remember');
const plumber     = require('gulp-plumber');
const newer       = require('gulp-newer');
const del         = require('del');
const beep        = require('beepbeep');

// Build related plugins
const zip = require('gulp-zip');

/**
  * Custom Error Handler.
  *
  * @param {Mixed} err Error.
  */
const errorHandler = (err) => {
	notify.onError('❌  ===> ERROR: <%= error.message %>')(err);
	beep();
};

/**
  * Task: `browser-sync`.
  *
  * Live Reloads, CSS injections, Localhost tunneling.
  * @link http://www.browsersync.io/docs/options/
  *
  * @param {Mixed} done Done.
  */
const browsersync = (done) => {
	browserSync.init({
		proxy: config.projectURL,
		open: config.browserAutoOpen,
		injectChanges: config.injectChanges,
		watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
	});
	done();
};

// Helper function to allow browser reload with Gulp 4.
const reload = (done) => {
	browserSync.reload();
	done();
};

/**
  * Task: `styles`.
  *
  * Compiles Sass, adds source maps, Auto-prefixes it
  * and Minifies CSS.
  *
  */
gulp.task('styles', () => {
	return gulp
		.src(config.styleSRC, { allowEmpty: true })
		.pipe(plumber(errorHandler))
		.pipe(sourceMaps.init())
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoPrefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(beautifyCss())
		.pipe(sourceMaps.write({ includeContent: true }))
		.pipe(sourceMaps.init({ loadMaps: true }))
		.pipe(sourceMaps.write('./'))
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifyCss({ maxLineLen: 10 }))
		.pipe(lineec())
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(notify({ message: '✅  \n===> Completed: Frontend Styles', onLast: true }));
});

/**
  * Task: `buildStyles`.
  *
  * Compiles Sass, Auto-prefixes and Minifies CSS.
  * Also merges Media Queries.
  *
  */
gulp.task('buildStyles', () => {
	return gulp
		.src(config.styleSRC, { allowEmpty: true })
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoPrefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(mmq({ log: true })) // Merge Media Queries.
		.pipe(beautifyCss())
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifyCss({ maxLineLen: 10 }))
		.pipe(lineec())
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(notify({ message: '✅  \n===> Built: Frontend Styles', onLast: true }));
});

/**
  * Task: `adminStyles`.
  *
  * Compiles Sass, adds source maps, Auto-prefixes it
  * and Minifies CSS.
  *
  */
gulp.task('adminStyles', () => {
	return gulp
		.src(config.styleAdminSRC, { allowEmpty: true })
		.pipe(plumber(errorHandler))
		.pipe(sourceMaps.init())
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoPrefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(beautifyCss())
		.pipe(sourceMaps.write({ includeContent: true }))
		.pipe(sourceMaps.init({ loadMaps: true }))
		.pipe(sourceMaps.write('./'))
		.pipe(gulp.dest(config.styleAdminDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifyCss({ maxLineLen: 10 }))
		.pipe(lineec())
		.pipe(gulp.dest(config.styleAdminDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(notify({ message: '✅  \n===> Completed: Admin Styles', onLast: true }));
});

/**
  * Task: `buildAdminStyles`.
  *
  * Compiles Sass, Auto-prefixes and Minifies CSS.
  * Also merges Media Queries.
  *
  */
gulp.task('buildAdminStyles', () => {
	return gulp
		.src(config.styleAdminSRC, { allowEmpty: true })
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoPrefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(mmq({ log: true })) // Merge Media Queries.
		.pipe(beautifyCss())
		.pipe(gulp.dest(config.styleAdminDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifyCss({ maxLineLen: 10 }))
		.pipe(lineec())
		.pipe(gulp.dest(config.styleAdminDestination))
		.pipe(filter('**/*.css'))
		.pipe(browserSync.stream())
		.pipe(notify({ message: '✅  \n===> Built: Admin Styles', onLast: true }));
});

/**
 * Task: `frontendJS`.
 *
 * Uses webpack to concatenate and uglify custom JS scripts.
 *
 */
gulp.task('frontendJS', () => {
	return gulp
		.src(config.jsCustomSRC, { since: gulp.lastRun('frontendJS') })
		.pipe(plumber(errorHandler))
		.pipe(remember(config.jsCustomSRC))
		.pipe(concat(config.jsCustomFile + '.js'))
		.pipe(webpackStream(webpackConfig), webpack)
		.pipe(lineec())
		.pipe(gulp.dest(config.jsCustomDestination))
		.pipe(notify({ message: '✅  \n===> Completed: Frontend JS', onLast: true }));
});

/**
  * Task: `images`.
  *
  * Minifies PNG, JPEG, GIF and SVG images.
  *
  */
gulp.task('images', () => {
	return (
		del('./src/images/**/*.db'),
		gulp
			.src(config.imgSRC)
			.pipe(newer(config.imgSRC))
			.pipe(
				cache(
					imagemin(
						[
							imagemin.gifsicle({ interlaced: true }),
							imagemin.jpegtran({ progressive: true }),
							imagemin.optipng({ optimizationLevel: 3 }), // 0-7 low-high.
							imagemin.svgo({
								plugins: [{ removeViewBox: true }, { cleanupIDs: false }]
							})
						],
						{
							verbose: true
						}
					)
				)
			)
			.pipe(gulp.dest(config.imgDST))
			.pipe(notify({ message: '✅  ===> Completed: Images Optimization', onLast: true }))
	);
});

/**
  * Task: `clear-images-cache`.
  *
  * Deletes the images cache. By running the next "images" task,
  * each image will be regenerated.
  *
  * @param {Mixed} done Done.
  */
gulp.task('clearCache', function (done) {
	return cache.clearAll(done);
});

/**
  * WP POT Translation File Generator.
  *
  */
gulp.task('translate', () => {
	return gulp
		.src(config.watchPhp)
		.pipe(sort())
		.pipe(
			wpPot({
				domain: config.textDomain,
				package: config.packageName,
				bugReport: config.bugReport,
				lastTranslator: config.lastTranslator,
				team: config.team
			})
		)
		.pipe(gulp.dest(config.translationDestination + '/' + config.translationFile))
		.pipe(notify({ message: '✅  \n===> Completed: WP Translation', onLast: true }));
});

/**
  * Watch Tasks.
  *
  * Watches for file changes and runs specific tasks.
  */
gulp.task(
	'run',
	gulp.parallel('styles', 'adminStyles', 'frontendJS', 'images', browsersync, () => {
		gulp.watch(config.watchPhp, reload);
		gulp.watch(config.watchStyles, gulp.parallel('styles'));
		gulp.watch(config.watchStyles, gulp.parallel('adminStyles'));
		gulp.watch(config.watchJsCustom, gulp.series('frontendJS', reload));
		gulp.watch(config.imgSRC, gulp.series('images', reload));
	})
);

/**
  * Clean Task.
  *
  * Delete files and folders.
  */
gulp.task('clean', () => {
	return del(config.build + '**/*');
});

/**
  * Build files Task.
  *
  * Build files and folders in the dist directory.
  */
gulp.task('buildFiles', () => {
	return gulp
		.src(config.buildInclude)
		.pipe(gulp.dest(config.build + config.projectName + '/'))
		.pipe(notify({ message: '✅  Built: Copy to Dist Folder', onLast: true }));
});

/**
  * Build Zip Task.
  *
  * Build Zip from the Build directory in dist
  */
gulp.task('buildZip', () => {
	return gulp
		.src(config.build + '/**/*')
		.pipe(zip(config.projectName + '.zip'))
		.pipe(gulp.dest(config.buildFinalZip))
		.pipe(notify({ message: '✅  Built: Zip File', onLast: true }));
});

/**
  * Build files from scratch Task.
  *
  * Build production file from the dist directory in root.
  *
  * @param {Mixed} done Done.
  */
gulp.task(
	'buildDist',
	gulp.series(
		'clean',
		'buildStyles',
		'buildAdminStyles',
		'frontendJS',
		'images',
		'translate',
		'buildFiles',
		'buildZip',
		function (done) {
			done();
		}
	)
);
