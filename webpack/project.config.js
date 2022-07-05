/**
 * This file holds the custom project configuration.
 * Feel free to edit as per your requirement.
 *
 * @since 1.0.0
 */
const path = require('path');

module.exports = (dir) => {
	return {
		/**
		 * Source Directory.
		 */
		projectJsPath: 'src/js',
		projectScssPath: 'src/scss',
		projectImagesPath: 'src/images',

		/**
		 * Output Directory.
		 */
		projectOutput: 'assets',

		/**
		 * Package Directory.
		 */
		projectPackageDir: 'dist',

		/**
		 * CSS File name
		 */
		css: [],

		/**
		 * JS File name
		 */
		js: ['frontend', 'backend'],

		/**
		 * Files & folders to be added to zip file.
		 */
		buildIncludes: [
			'assets',
			'includes',
			'languages',
			'page-templates',
			'templates',
			'vendor',
			'views',
			'LICENSE',
			'README.md',
			'index.php',
			path.basename(path.resolve(dir)) + '.php', // plugin-slug.php.
		],

		/**
		 * Translation.
		 */
		packageName: 'my-plugin-boilerplate',
		textDomain: 'my-plugin-text-domain',
		translationSrc: '**/**/**/*.php',
		translationDirectory:
			/**
			 * languages/plugin-slug.pot
			 */
			'languages/' + path.basename(path.resolve(dir)) + '.pot',

		/**
		 * Browser Sync.
		 */
		browserSyncEnable: true,
		localhost: 'http://latest.test/',
	};
};
