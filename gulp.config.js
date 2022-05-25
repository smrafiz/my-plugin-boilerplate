/**
 * Gulp Configuration File
 *
 * @author S.M. Rafiz <https://github.com/smrafiz>
 * @package Prefix\MyPluginBoilerplate
 * @version 1.0.0
 */

module.exports = {
	projectName: 'my-plugin-boilerplate',
	projectURL: 'http://localhost.test/',
	productURL: './',
	browserAutoOpen: false,
	injectChanges: true,

	// Style options.
	styleSRC: './src/scss/frontend.scss',
	styleAdminSRC: './src/scss/admin.scss',
	styleDestination: './assets/css/',
	styleAdminDestination: './assets/css/',
	outputStyle: 'expanded',
	errLogToConsole: true,
	precision: 10,

	// JS Custom options.
	jsCustomSRC: './src/js/*.js',
	jsCustomDestination: './assets/js/',
	jsCustomFile: 'frontend',

	// Images options.
	imgSRC: './src/images/**/*.{png,jpg,gif}',
	imgDST: './assets/images/',

	// Build options.
	build: './dist/',
	buildVendorSRC: 'vendor/**',
	buildVendorDest: './dist/vendor',
	buildInclude: [
		// Include common file types.
		'**/*.php',
		'**/*.html',
		'**/*.css',
		'**/*.js',
		'**/*.svg',
		'**/*.png',
		'**/*.jpg',
		'**/*.gif',
		'**/*.ttf',
		'**/*.otf',
		'**/*.eot',
		'**/*.woff',
		'**/*.woff2',
		'**/*.pot',
		'**/*/installed.json',
		'**/*/LICENSE',
		'LICENSE',
		'README.md',
		'**/*/composer.json',
		'**/*/*.mo',
		'**/*/*.po',
		'**/*/*.scss',

		// Exclude files and folders.
		'!**/.*',
		'!node_modules/**/*',
		'!dist/**/*',
		'!gulp.config.js',
		'!gulpfile.js',
		'!src/images/**/*',
		'!src/scss/**/*',
		'!src/js/**/*',
		'!webpack.config.js'
	],
	buildFinalZip: './dist/',

	// Watch files paths.
	watchStyles: './src/scss/**/*.scss',
	watchJsCustom: './src/js/**/*.js',
	watchPhp: './**/*.php',

	// Translation options.
	textDomain: 'my-plugin',
	translationFile: 'my-plugin.pot',
	translationDestination: './languages',
	packageName: 'My Plugin Boilerplate',
	bugReport: 'https://github.com/smrafiz/my-plugin/issues',
	lastTranslator: 'S.M. Rafiz',
	team: 'S.M. Rafiz',

	// Browsers for auto-prefixing.
	BROWSERS_LIST: [
		'last 20 version',
		'> 1%',
		'ie >= 11',
		'last 6 Android versions',
		'last 20 ChromeAndroid versions',
		'last 20 Chrome versions',
		'last 20 Firefox versions',
		'last 20 Safari versions',
		'last 20 iOS versions',
		'last 20 Edge versions',
		'last 20 Opera versions'
	]
};
