/**
 * Webpack configurations for the production environment
 * based on the script from package.json
 * Run with: "npm run prod" or or "npm run prod:watch"
 *
 * @since 1.0.0
 */
const RemovePlugin = require('remove-files-webpack-plugin');

module.exports = (projectOptions) => {
	process.env.NODE_ENV = 'production'; // Set environment level to 'production'

	/**
	 * The base skeleton
	 */
	const Base = require('./config.base')(projectOptions);
	const zip = require('./zip.script')(projectOptions);

	/**
	 * CSS rules
	 */
	const cssRules = {
		...Base.cssRules,
		...{
			// add CSS rules for production here
		},
	};

	/**
	 * JS rules
	 */
	const jsRules = {
		...Base.jsRules,
		...{
			// add JS rules for production here
		},
	};

	/**
	 * Image rules
	 */
	const imageRules = {
		...Base.imageRules,
		...{
			// add image rules for production here
		},
	};

	/**
	 * Optimizations rules
	 */
	const optimizations = {
		...Base.optimizations,
		...{
			splitChunks: {
				cacheGroups: {
					styles: {
						// Configured for PurgeCSS
						name: 'styles',
						test: /\.css$/,
						chunks: 'all',
						enforce: true,
					},
				},
			},
			// add optimizations rules for production here
		},
	};

	/**
	 * Plugins
	 */
	const plugins = [
		...Base.plugins,
		...[
			// add plugins for production here
			new RemovePlugin({
				/**
				 * After compilation permanently removes
				 * all maps files in `./assets` folder and
				 * all subfolders (e.g. `./assets/js`).
				 */
				before: {
					include: [projectOptions.projectPackageDir],
					test: [
						{
							folder: projectOptions.projectOutput,
							method: (absoluteItemPath) => {
								return new RegExp(/\.map$/, 'm').test(
									absoluteItemPath
								);
							},
							recursive: true,
						},
					],
				},
			}),
		],
	];

	plugins.push({
		apply: (compiler) => {
			compiler.hooks.afterDone.tap('AfterBuild', () => {
				zip.copyScript();
			});
		},
	});

	/**
	 * Add sourcemap for production if enabled
	 */
	const sourceMap = { devtool: false };
	if (
		projectOptions.projectSourceMaps.enable === true &&
		(projectOptions.projectSourceMaps.env === 'prod' ||
			projectOptions.projectSourceMaps.env === 'dev-prod')
	) {
		sourceMap.devtool = projectOptions.projectSourceMaps.devtool;
	}

	/**
	 * The configuration that's being returned to Webpack
	 */
	return {
		mode: 'production',
		entry: {
			...projectOptions.projectJs.entry,
			...projectOptions.projectCss.entry,
		},
		output: {
			path: projectOptions.projectOutput,
			filename: projectOptions.projectJs.filename,
		},
		devtool: sourceMap.devtool,
		optimization: optimizations,
		module: { rules: [cssRules, jsRules, imageRules] },
		plugins: plugins,
	};
};
