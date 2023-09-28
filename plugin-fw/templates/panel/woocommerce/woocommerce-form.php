<?php
/**
 * The Template for displaying the WooCommerce form.
 *
 * @var DIDO_Plugin_Panel_WooCommerce $this       The AJ WooCommerce Panel.
 * @var string                       $option_key The current option key ( see DIDO_Plugin_Panel::get_current_option_key() ).
 * @package    AJ\PluginFramework\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$content_class = apply_filters( 'dido_admin_panel_content_class', 'dido-admin-panel-content-wrap' );
$container_id  = $this->settings['page'] . '_' . $option_key;
$reset_warning = __( 'If you continue with this action, you will reset all options in this page.', 'aj-plugin-fw' ) . '\n' . __( 'Are you sure?', 'aj-plugin-fw' );
?>

<div id="<?php echo esc_attr( $container_id ); ?>" class="aj-plugin-fw  dido-admin-panel-container">

	<?php do_action( 'dido_framework_before_print_wc_panel_content', $option_key ); ?>

	<div class="<?php echo esc_attr( $content_class ); ?>">
		<form id="plugin-fw-wc" method="post">

			<?php $this->add_fields(); ?>

			<p class="submit" style="float: left;margin: 0 10px 0 0;">
				<?php wp_nonce_field( 'dido_panel_wc_options_' . $this->settings['page'], 'dido_panel_wc_options_nonce' ); ?>
				<input class="button-primary" type="submit" value="<?php esc_html_e( 'Save Changes', 'aj-plugin-fw' ); ?>"/>
			</p>
		</form>
		<form id="plugin-fw-wc-reset" method="post">
			<input type="hidden" name="dido-action" value="wc-options-reset"/>
			<?php wp_nonce_field( 'aj_wc_reset_options_' . $this->settings['page'], 'aj_wc_reset_options_nonce' ); ?>
			<input type="submit" name="dido-reset" class="button-secondary" value="<?php esc_html_e( 'Reset Defaults', 'aj-plugin-fw' ); ?>"
					onclick="return confirm('<?php echo esc_attr( $reset_warning ); ?>');"/>
		</form>
	</div>

	<?php do_action( 'dido_framework_after_print_wc_panel_content', $option_key ); ?>

</div>
