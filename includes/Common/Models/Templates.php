<?php
/**
 * Model Class: Templates
 *
 * This class is responsible for loading frontend templates.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Models;

/**
 * Class: Templates
 *
 * @package ThePluginName\App\Backend
 * @since 1.0.0
 */
class Templates {

	/**
	 * Internal use only: Store located template paths.
	 *
	 * @var array
	 */
	private $path_cache = [];

	/**
	 * Retrieve a template part, modified version of:
	 *
	 * @url https://github.com/GaryJones/Gamajo-Template-Loader
	 *
	 * @param string $slug Template slug.
	 * @param string $name Optional. Template variation name. Default null.
	 * @param array  $args Optional. Template args. Default empty.
	 * @param bool   $load Optional. Whether to load template. Default true.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get( $slug, $name = null, $args = [], $load = true ) {
		// Execute code for this part.
		do_action( 'get_template_part_' . $slug, $slug, $name, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		do_action( 'my_plugin_boilerplate_get_template_part_' . $slug, $slug, $name, $args );

		// Get files names of templates, for given slug and name.
		$templates = $this->getFileNames( $slug, $name, $args );

		// Return the part that is found.
		return $this->locate( $templates, $load, false, $args );
	}

	/**
	 * Given a slug and optional name, create the file names of templates, modified version of:
	 *
	 * @url https://github.com/GaryJones/Gamajo-Template-Loader
	 *
	 * @param string $slug Template slug.
	 * @param string $name Template variation name.
	 * @param array  $args Template args.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected function getFileNames( $slug, $name, $args ) {
		$templates = [];

		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}

		$templates[] = $slug . '.php';

		/**
		 * Allow template choices to be filtered.
		 *
		 * The resulting array should be in the order of most specific first, to least specific last.
		 * e.g. 0 => recipe-instructions.php, 1 => recipe.php
		 *
		 * @param array $templates Names of template files that should be looked for, for given slug and name.
		 * @param string $slug Template slug.
		 * @param string $name Template variation name.
		 * @since 1.0.0
		 */
		return apply_filters( 'my_plugin_boilerplate_get_template_part', $templates, $slug, $name, $args );
	}


	/**
	 * Retrieve the name of the highest priority template file that exists, modified version of:
	 *
	 * @url https://github.com/GaryJones/Gamajo-Template-Loader
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template
	 * is not found in either of those, it looks in the theme-compat
	 * folder last.
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 * @param bool         $load If true the template file will be loaded if it is found.
	 * @param bool         $require_once Whether to require_once or require. Default true.
	 *                                             Has no effect if $load is false.
	 * @param array        $args Template args.
	 * @return string The template filename if one is located.
	 * @since 1.0.0
	 */
	public function locate( $template_names, $load = false, $require_once = true, $args = [] ) {
		// Use $template_names as a cache key - either first element of array or the variable itself if it's a string.
		$cache_key = is_array( $template_names ) ? $template_names[0] : $template_names;

		// If the key is in the cache array, we've already located this file.
		if ( isset( $this->path_cache[ $cache_key ] ) ) {
			$located = $this->path_cache[ $cache_key ];
		} else {
			// No file found yet.
			$located = false;

			// Remove empty entries.
			$template_names = array_filter( (array) $template_names );
			$template_paths = $this->getPaths();

			// Try to find a template file.
			foreach ( $template_names as $template_name ) {
				// Trim off any slashes from the template name.
				$template_name = ltrim( $template_name, '/' );

				// Try locating this template file by looping through the template paths.
				foreach ( $template_paths as $template_path ) {
					if ( file_exists( $template_path . $template_name ) ) {
						$located = $template_path . $template_name;
						// Store the template path in the cache.
						$this->path_cache[ $cache_key ] = $located;
						break 2;
					}
				}
			}
		}

		if ( $load && $located ) {
			load_template( $located, $require_once, $args );
		}

		return $located;
	}

	/**
	 * Return a list of paths to check for template locations,
	 * modified version of:
	 *
	 * @url https://github.com/GaryJones/Gamajo-Template-Loader
	 *
	 * Default is to check in a child theme (if relevant) before a parent theme, so that themes which inherit from a
	 * parent theme can just overload one file. If the template is not found in either of those, it looks in the
	 * theme-compat folder last.
	 *
	 * @return mixed|void
	 * @since 1.0.0
	 */
	protected function getPaths() {
		$theme_directory = trailingslashit( my_plugin_boilerplate()->getData()['ext_template_folder'] );

		$file_paths = [
			10  => trailingslashit( get_template_directory() ) . $theme_directory,
			100 => my_plugin_boilerplate()->templatesPath(),
		];

		// Only add this conditionally, so non-child themes don't redundantly check active theme twice.
		if ( get_stylesheet_directory() !== get_template_directory() ) {
			$file_paths[1] = trailingslashit( get_stylesheet_directory() ) . $theme_directory;
		}

		/**
		 * Allow ordered list of template paths to be amended.
		 *
		 * @param array $var Default is directory in child theme at index 1, parent theme at 10, and plugin at 100.
		 * @since 1.0.0
		 */
		$file_paths = apply_filters( 'my_plugin_boilerplate_template_paths', $file_paths );

		// Sort the file paths based on priority.
		ksort( $file_paths, SORT_NUMERIC );

		return array_map( 'trailingslashit', $file_paths );
	}
}
