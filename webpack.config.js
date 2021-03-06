/**
 * This is the main entrypoint for Webpack configuration.
 *
 * @since 1.0.0
 */
const path = require('path');
const wpPot = require('wp-pot');
const conf = require('./webpack/project.config')(__dirname);

// Paths to find our files and provide BrowserSync functionality.
const projectPaths = {
	/**
	 * Directory Setup.
	 */
	projectDir: __dirname,
	projectSlug: path.basename(path.resolve(__dirname)),
	projectRoot: path.resolve(__dirname),
	projectWebpack: path.resolve(__dirname, 'webpack/configs'),

	/**
	 * Source directory Setup.
	 */
	projectJsPath: path.resolve(__dirname, conf.projectJsPath),
	projectScssPath: path.resolve(__dirname, conf.projectScssPath),
	projectImagesPath: path.resolve(__dirname, conf.projectImagesPath),

	/**
	 * Asset (Output) directory Setup.
	 */
	projectOutput: path.resolve(__dirname, conf.projectOutput),

	/**
	 * Package directory Setup.
	 */
	projectPackageDir: path.resolve(__dirname, conf.projectPackageDir),

	/**
	 * Files & folders to be added to zip file.
	 */
	buildIncludes: conf.buildIncludes,
};

/**
 * Project Configuration.
 */
const projectConfiguration = {
	packageName: conf.packageName,
	textDomain: conf.textDomain,
	translationSrc: conf.translationSrc,
	translationDirectory: conf.translationDirectory,
	proxy: conf.localhost,
	localhost: conf.localhost,
	bsEnable: conf.browserSyncEnable,

	/**
	 * CSS File name
	 */
	css: conf.css,

	/**
	 * JS File name
	 */
	js: conf.js,
};

const cssFiles = {};
const jsFiles = {};

projectConfiguration.css.forEach((file) => {
	cssFiles[file] = projectPaths.projectScssPath + '/' + file + '.scss';
});

projectConfiguration.js.forEach((file) => {
	jsFiles[file] = projectPaths.projectJsPath + '/' + file + '.js';
});

// Files to bundle
const projectFiles = {
	// BrowserSync settings
	browserSync: {
		enable: projectConfiguration.bsEnable, // enable or disable browserSync
		host: 'localhost',
		port: 3000,
		mode: 'proxy', // proxy | server
		server: true, // can be ignored if using proxy
		proxy: projectConfiguration.localhost,
		// BrowserSync will automatically watch for changes to any files connected to our entry,
		// including both JS and Sass files. We can use this property to tell BrowserSync to watch
		// for other types of files, in this case PHP files, in our project.
		files: '**/**/**.php',
		reload: true, // Set false to prevent BrowserSync from reloading and let Webpack Dev Server take care of this
		// browse to http://localhost:3000/ during development,
		injectCss: true,
	},
	// JS configurations for development and production
	projectJs: {
		eslint: true, // enable or disable eslint  | this is only enabled in development env.
		filename: 'js/[name].min.js',
		entry: jsFiles,
		rules: {
			test: /\.m?js$/,
		},
	},
	// CSS configurations for development and production
	projectCss: {
		postCss: projectPaths.projectWebpack + '/postcss.config.js',
		stylelint: true, // enable or disable stylelint | this is only enabled in development env.
		filename: 'css/[name].min.css',
		use: 'sass', // sass || postcss
		entry: cssFiles,
		// ^ If you want to change from Sass to PostCSS or PostCSS to Sass then you need to change the
		// styling files which are being imported in "assets/src/js/frontend.js" and "assets/src/js/backend.js".
		// So change "import '../sass/backend.scss'" to "import '../postcss/backend.pcss'" for example
		rules: {
			sass: {
				test: /\.s[ac]ss$/i,
			},
			postcss: {
				test: /\.pcss$/i,
			},
		},
	},
	// Source Maps configurations
	projectSourceMaps: {
		// Sourcemaps are nice for debugging but takes lots of time to compile,
		// so we disable this by default and can be enabled when necessary
		enable: true,
		env: 'dev', // dev | dev-prod | prod
		// ^ Enabled only for development on default, use "prod" to enable only for production
		// or "dev-prod" to enable it for both production and development
		devtool: 'source-map', // type of sourcemap, see more info here: https://webpack.js.org/configuration/devtool/
		// ^ If "source-map" is too slow, then use "cheap-source-map" which struck a good balance between build performance and debuggability.
	},
	// Images configurations for development and production
	projectImages: {
		rules: {
			test: /\.(jpe?g|png|gif|svg)$/i,
		},
		// Optimization settings
		minimizerOptions: {
			// Lossless optimization with custom option
			// Feel free to experiment with options for better result for you
			// More info here: https://webpack.js.org/plugins/image-minimizer-webpack-plugin/
			plugins: [
				['gifsicle', { interlaced: true }],
				['jpegtran', { progressive: true }],
				['optipng', { optimizationLevel: 5 }],
				[
					'svgo',
					{
						plugins: [{ removeViewBox: false }],
					},
				],
			],
		},
	},
};

// Merging the projectFiles & projectPaths objects
const projectOptions = {
	...projectPaths,
	...projectFiles,
	projectConfig: {
		// add extra options here
		packageName: projectConfiguration.projectName,
		textDomain: projectConfiguration.textDomain,
		lastTranslator: 'S.M. Rafiz <s.m.rafiz@gmail.com>',
		team: 'S.M. Rafiz <s.m.rafiz@gmail.com>',
		dest: projectConfiguration.translationDirectory,
		src: projectConfiguration.translationSrc,
	},
};

// Get the development or production setup based
// on the script from package.json
module.exports = (env) => {
	if (env.NODE_ENV === 'distribution') {
		return require('./webpack/configs/config.distribution')(projectOptions);
	}

	if (env.NODE_ENV === 'production') {
		wpPot({
			domain: projectOptions.projectConfig.textDomain,
			package: projectOptions.projectConfig.packageName,
			lastTranslator: projectOptions.projectConfig.lastTranslator,
			team: projectOptions.projectConfig.team,
			destFile: projectOptions.projectConfig.dest,
			src: projectOptions.projectConfig.src,
		});

		return require('./webpack/configs/config.production')(projectOptions);
	}

	return require('./webpack/configs/config.development')(projectOptions);
};
