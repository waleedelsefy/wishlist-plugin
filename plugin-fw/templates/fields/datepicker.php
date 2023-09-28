<?php
/**
 * Template for displaying the datepicker field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $value, $data, $custom_attributes ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'value', 'data', 'custom_attributes' );

$class = ! empty( $class ) ? $class : 'aj-plugin-fw-datepicker';
?>
<input type="text"
		name="<?php echo esc_attr( $name ); ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		autocomplete="off"
	<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
/>
