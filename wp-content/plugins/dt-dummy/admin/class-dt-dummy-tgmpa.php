<?php
/**
 * TGMPA facade.
 *
 * @since   2.0.0
 * @package dt-dummy
 */

class DT_Dummy_TGMPA implements DT_Dummy_Plugins_Checker_Interface {

	/**
	 * Returns false if any of $plugins is not active, in other cases returns true.
	 * 
	 * @param  array   $plugins
	 * @return boolean
	 */
	public function is_plugins_active( $plugins = array() ) {
		global $tgmpa;

		if ( $plugins ) {
			foreach ( $plugins as $plugin_slug ) {
				if ( ! $tgmpa->is_plugin_active( $plugin_slug ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * If all plugins installed and active - returns empty string. In other cases returns url to tgmpa plugins page.
	 * 
	 * @return string
	 */
	public function get_install_plugins_page_link() {
		global $tgmpa;

		if ( $tgmpa->is_tgmpa_complete() ) {
			return '';
		}

		return add_query_arg( 'page', $tgmpa->menu, admin_url( $tgmpa->parent_slug ) );
	}

	/**
	 * Returns $slug plugin name if it is registered, in other cases returns $slug.
	 * 
	 * @param  string $slug
	 * @return string
	 */
	public function get_plugin_name( $slug ) {
		global $tgmpa;

		if ( isset( $tgmpa->plugins[ $slug ] ) ) {
			return $tgmpa->plugins[ $slug ]['name'];
		}

		return $slug;
	}

	/**
	 * Checks if $tgmpa global is not empty.
	 * 
	 * @return boolean
	 */
	public static function is_tgmpa_active() {
		return ( ! empty( $GLOBALS['tgmpa'] ) );
	}
}
