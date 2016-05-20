<?php
/**
 * Dummy required plugins not installed view.
 *
 * @package dt-dummy
 * @since   2.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$plugins_info_text_1 = __( 'To import this demo content you need to %s:', 'dt-dummy' );
$plugins_info_text_2 = __( 'install and activate following plugins', 'dt-dummy' );

$tmpa_link = $this->plugins_checker()->get_install_plugins_page_link();
if ( $tmpa_link ) {
	$plugins_info_text_2 = '<a href="' . esc_url( $tmpa_link ) . '">' . $plugins_info_text_2 . '</a>';
}
?>

	<div class="dt-dummy-controls-block">
		<h4><?php printf( $plugins_info_text_1, $plugins_info_text_2 ); ?></h4>
		<p>
			<?php
			$plugins_names = array();
			foreach ( $dummy_info->get( 'req_plugins' ) as $plugin_slug ) {
				$plugins_names[] = $this->plugins_checker()->get_plugin_name( $plugin_slug );
			}

			echo implode( ', ', $plugins_names );
			?>
		</p>
	</div>
