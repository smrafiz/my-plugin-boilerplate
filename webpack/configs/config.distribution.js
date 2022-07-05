/**
 * Webpack configurations for the distribution environment
 * based on the script from package.json
 * Run with: "npm run zip"
 *
 * @since 1.0.0
 */

module.exports = (projectOptions) => {
	process.env.NODE_ENV = 'distribution'; // Set environment level to 'distribution'

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
	};

	/**
	 * JS rules
	 */
	const jsRules = {
		...Base.jsRules,
	};

	/**
	 * Image rules
	 */
	const imageRules = {
		...Base.imageRules,
	};

	/**
	 * Optimizations rules
	 */
	const optimizations = {
		...Base.optimizations,
	};

	/**
	 * Plugins
	 */
	const plugins = [...Base.plugins];

	plugins.push({
		apply: (compiler) => {
			compiler.hooks.afterDone.tap('AfterBuild', () => {
				zip.zipScript();
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
