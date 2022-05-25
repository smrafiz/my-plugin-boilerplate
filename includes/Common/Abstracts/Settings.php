<?php
/**
 * Abstract Class: Settings
 *
 * This class taps into WordPress Settings API to create admin pages.
 *
 * @package Prefix\MyPluginBoilerplate
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Prefix\MyPluginBoilerplate\Common\Abstracts;

use Prefix\MyPluginBoilerplate\Common\Abstracts\Base;

/**
 * Abstract Class: Settings
 *
 * @since 1.0.0
 */
abstract class Settings extends Base {

	/**
	 * Admin pages.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $adminPages = [];

	/**
	 * Admin subpages.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $adminSubpages = [];

	/**
	 * Admin settings.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $settings = [];

	/**
	 * Settings sections.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $sections = [];

	/**
	 * Settings fields.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $fields = [];

	/**
	 * Registers the class.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register() {
		$this->adminPages()->adminSettings();

		if ( ! empty( $this->adminPages ) || ! empty( $this->adminSubpages ) ) {
			\add_action( 'admin_menu', [ $this, 'addAdminMenu' ] );
		}

		if ( ! empty( $this->settings ) ) {
			\add_action( 'admin_init', [ $this, 'registerCustomFields' ] );
		}
	}

	/**
	 * Admin Pages & Subpages.
	 *
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function adminPages() {
		$this
			->addPages( $this->setPages() )
			->addSubPages( $this->setSubPages() );

		return $this;
	}

	/**
	 * Admin Settings, Sections & Fields.
	 *
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function adminSettings() {
		$this
			->addSettings( $this->setSettings() )
			->addSections( $this->setSections() )
			->addFields( $this->setFields() );

		return $this;
	}

	/**
	 * Method to add admin pages.
	 *
	 * @param array $pages Admin pages.
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function addPages( array $pages ) {
		$this->adminPages = array_merge( $this->adminPages, $pages );

		return $this;
	}

	/**
	 * Method to add admin sub pages.
	 *
	 * @param array $subPages Admin subpages.
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function addSubPages( array $subPages ) {
		if ( empty( $this->adminPages ) ) {
			return $this;
		}

		foreach ( $this->adminPages as $page ) {
			$this->adminSubpages[] = [
				'parent_slug' => $page['menu_slug'],
				'page_title'  => $page['page_title'],
				'menu_title'  => ( $page['top_menu_title'] ) ? $page['top_menu_title'] : $page['menu_title'],
				'capability'  => $page['capability'],
				'menu_slug'   => $page['menu_slug'],
				'callback'    => $page['callback'],
			];
		}

		$this->adminSubpages = array_merge( $this->adminSubpages, $subPages );

		return $this;
	}

	/**
	 * Method to add admin settings.
	 *
	 * @param array $settings Admin settings.
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function addSettings( array $settings ) {
		$this->settings = array_merge( $this->settings, $settings );

		return $this;
	}

	/**
	 * Method to add admin sections.
	 *
	 * @param array $sections Admin sections.
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function addSections( array $sections ) {
		$this->sections = array_merge( $this->sections, $sections );

		return $this;
	}

	/**
	 * Method to add admin fields.
	 *
	 * @param array $fields Admin fields.
	 * @return Settings
	 * @since 1.0.0
	 */
	protected function addFields( array $fields ) {
		$this->fields = array_merge( $this->fields, $fields );

		return $this;
	}

	/**
	 * Method to add admin menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function addAdminMenu() {
		foreach ( $this->adminPages as $page ) {
			\add_menu_page(
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				$page['menu_slug'],
				$page['callback'],
				$page['icon_url'],
				$page['position']
			);
		}

		foreach ( $this->adminSubpages as $page ) {
			\add_submenu_page(
				$page['parent_slug'],
				$page['page_title'],
				$page['menu_title'],
				$page['capability'],
				$page['menu_slug'],
				$page['callback']
			);
		}
	}

	/**
	 * Registers custom fields with callbacks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function registerCustomFields() {
		// register setting.
		foreach ( $this->settings as $setting ) {
			\register_setting(
				$setting['option_group'],
				$setting['option_name'],
				( isset( $setting['callback'] ) ? $setting['callback'] : '' )
			);
		}

		// add settings section.
		foreach ( $this->sections as $section ) {
			\add_settings_section(
				$section['id'],
				$section['title'],
				( isset( $section['callback'] ) ? $section['callback'] : '' ),
				$section['page']
			);
		}

		// add settings field.
		foreach ( $this->fields as $field ) {
			\add_settings_field(
				$field['id'],
				$field['title'],
				( isset( $field['callback'] ) ? $field['callback'] : '' ),
				$field['page'],
				$field['section'],
				( isset( $field['args'] ) ? $field['args'] : '' )
			);
		}
	}

	/**
	 * Method to accumulate admin pages list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract protected function setPages();

	/**
	 * Method to accumulate admin pages list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract protected function setSubPages();

	/**
	 * Method to accumulate settings list.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract protected function setSettings();

	/**
	 * Method to accumulate sections list
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract protected function setSections();

	/**
	 * Method to accumulate fields list
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract protected function setFields();
}
