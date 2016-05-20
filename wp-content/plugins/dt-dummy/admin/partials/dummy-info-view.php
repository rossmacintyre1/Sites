<?php
/**
 * Dummy info view.
 *
 * @package dt-dummy
 * @since   2.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( $this->plugins_checker()->is_plugins_active( array( 'revslider' ) ) ) :
?>
	<div class="dt-dummy-controls-block dt-dummy-info-content">

		<p><strong><?php printf( __( 'Please note that Slider Revolution content must be imported separately via <a href="%s" target="_blank">interface</a>.', 'dt-dummy' ), admin_url( 'admin.php?page=revslider' ) ); ?></strong></p>

	</div>

<?php
endif;

//$checkbox_status = ' checked="checked"';
$checkbox_status = '';

/* Dummy info content */
$top_content = $dummy_info->get( 'top_content' );

if ( $top_content ) :
?>

	<div class="dt-dummy-controls-block dt-dummy-info-content">

		<?php echo $top_content; ?>

	</div>

<?php endif; ?>

<?php

$main_content = $dummy_content->get_main_content( $content_part_id );

?>

<?php if ( ! $main_content->is_empty() ) : ?>

	<div class="dt-dummy-controls-block">
		<h4><?php _e( 'Main content:', $this->plugin_name ); ?></h4>

		<?php foreach ( $main_content->as_array() as $dummy_id=>$dummy ) : ?>

			<label><input type="checkbox" name="<?php echo esc_attr( $dummy_id ); ?>"<?php echo $checkbox_status; ?> value="1" /><?php echo dt_dummy_get_content_nice_name( $dummy_id, $dummy ); ?></label>

		<?php endforeach; ?>

	</div>

<?php endif; ?>

<?php

$wc_content = $dummy_content->get_wc_content( $content_part_id );

?>

<?php if ( dt_dummy_is_wc_active() && ! $wc_content->is_empty() ) : ?>

	<div class="dt-dummy-controls-block">
		<h4><?php _e( 'Woocommerce content:', $this->plugin_name ); ?></h4>

		<?php foreach ( $wc_content->as_array() as $dummy_id=>$dummy ) : ?>

			<label><input type="checkbox" name="<?php echo esc_attr( $dummy_id ); ?>"<?php echo $checkbox_status; ?> value="1" /><?php echo dt_dummy_get_content_nice_name( $dummy_id, $dummy ); ?></label>

		<?php endforeach; ?>

	</div>

<?php endif; ?>

	<div class="dt-dummy-controls-block">
		<h4><?php _e( 'Extra settings:', $this->plugin_name ); ?></h4>

			<label><input type="checkbox" name="import_theme_options" value="1" /><?php _e( 'Import theme options', 'dt-dummy' ); ?></label>

	</div>

	<div class="dt-dummy-controls-block">
		<h4><?php _e( 'Assign posts to an existing user:', $this->plugin_name ); ?></h4>

		<?php wp_dropdown_users( array(
			'class' => 'dt-dummy-content-user',
			'id' => 'dt-dummy-content-user-' . $content_part_id,
			'selected' => get_current_user_id()
		) ); ?>

	</div>

	<div class="dt-dummy-controls-block dt-dummy-control-buttons">
		<div class="dt-dummy-button-wrap">
			<a href="#" class="button button-primary dt-dummy-button-import"><?php _e( 'Import content', $this->plugin_name ); ?></a><span class="spinner"></span>
		</div>
	</div>

<?php
/* Dummy info content */
$bottom_content = $dummy_info->get( 'bottom_content' );

if ( $bottom_content ) :
?>

	<div class="dt-dummy-controls-block dt-dummy-info-content">

		<?php echo $bottom_content; ?>

	</div>

<?php endif; ?>