<?php
/**
 * Template for displaying the radio field
 *
 * @var array $field The field.
 * @since   3.0.13
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $value, $options, $custom_attributes, $data ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'value', 'options', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : '';
$class = 'aj-plugin-fw-radio ' . $class;

?>
<div id="<?php echo esc_attr( $field_id ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		data-value="<?php echo esc_attr( $value ); ?>"
		data-type="radio"
	<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
>
	<?php foreach ( $options as $key => $label ) : ?>
		<?php
		$radio_id = $field_id . '-' . sanitize_key( $key );
		?>
		<div class="aj-plugin-fw-radio__row">
			<input type="radio" id="<?php echo esc_attr( $radio_id ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $key ); ?>"
				<?php checked( $key, $value ); ?>
			/>
			<label for="<?php echo esc_attr( $radio_id ); ?>">
				<?php
				// HTML allowed!
				echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</label>
		</div>
	<?php endforeach; ?>
</div>
