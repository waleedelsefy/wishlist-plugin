<?php
/**
 * Template for displaying the onoff field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $std, $value, $custom_attributes, $data, $desc_inline ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'std', 'value', 'custom_attributes', 'data', 'desc-inline' );

?>
<div class="aj-plugin-fw-onoff-container <?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>"
	<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
>
	<input type="checkbox" id="<?php echo esc_attr( $field_id ); ?>"
			class="on_off"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
		<?php if ( isset( $std ) ) : ?>
			data-std="<?php echo esc_attr( $std ); ?>"
		<?php endif; ?>
		<?php checked( true, aj_plugin_fw_is_true( $value ) ); ?>
		<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	/>
	<span class="aj-plugin-fw-onoff"
			data-text-on="<?php echo esc_attr_x( 'YES', 'YES/NO button: use MAX 4 characters!', 'aj-plugin-fw' ); ?>"
			data-text-off="<?php echo esc_attr_x( 'NO', 'YES/NO button: use MAX 4 characters!', 'aj-plugin-fw' ); ?>"></span>
</div>

<?php if ( isset( $desc_inline ) ) : ?>
	<span class='description inline'><?php echo wp_kses_post( $desc_inline ); ?></span>
<?php endif; ?>
