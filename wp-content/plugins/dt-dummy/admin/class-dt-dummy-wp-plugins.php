<?php
/**
 * WP plugins facade.
 *
 * @since 2.0.0
 * @package dt-dummy
 */

class DT_Dummy_WP_Plugins implements DT_Dummy_Plugins_Checker_Interface {

	/**
	 * array( 'slug' => 'name' )
	 * 
	 * @var array
	 */
	protected $plugins_names = array();

	public function __construct() {
		$bundled_plugins_fname = trailingslashit( PRESSCORE_PLUGINS_DIR ) . 'plugins.php';
		if ( is_readable( $bundled_plugins_fname ) ) {
			$this->plugins_names = include $bundled_plugins_fname;
			$this->plugins_names = wp_list_pluck( $this->plugins_names, 'name', 'slug' );
		}
	}

	/**
	 * Returns false if any of $plugins is not active, in other cases returns true.
	 * 
	 * @param  array   $plugins
	 * @return boolean
	 */
	public function is_plugins_active( $plugins = array() ) {
		if ( $plugins ) {
			foreach ( $plugins as $plugin_slug ) {
				if ( ! is_plugin_active( $plugin_slug ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Returns wp plugins page url.
	 * 
	 * @return string
	 */
	public function get_install_plugins_page_link() {
		return admin_url( 'plugins.php' );
	}

	/**
	 * Returns $slug plugin name if it is bundled with theme, in other cases returns $slug.
	 * 
	 * @param  string $slug
	 * @return string
	 */
	public function get_plugin_name( $slug ) {
		if ( isset( $this->plugins_names[ $slug ] ) ) {
			return $this->plugins_names[ $slug ];
		}

		return $slug;
	}

}
