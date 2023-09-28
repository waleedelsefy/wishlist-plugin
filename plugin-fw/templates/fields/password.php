<?php
/**
 * Template for displaying the password field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $std, $value, $custom_attributes, $data ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'std', 'value', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : 'aj-plugin-fw-text-input';
$class = $class . ' aj-password';
?>
<div class="aj-password-wrapper">
	<input type="password" id="<?php echo esc_attr( $field_id ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
		<?php if ( isset( $std ) ) : ?>
			data-std="<?php echo esc_attr( $std ); ?>"
		<?php endif; ?>
		<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
	/>
	<span class="aj-password-eye"></span>
</div>
