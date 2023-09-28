<?php
/**
 * Template for displaying the date-format field
 *
 * @var array $field The field.
 * @since   3.1.30
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $js, $format, $value, $data, $custom_attributes ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'js', 'format', 'value', 'data', 'custom_attributes' );

$class = isset( $class ) ? $class : '';
$class = 'aj-plugin-fw-radio aj-plugin-fw-date-format ' . $class;

$format  = isset( $format ) ? $format : 'date';
$options = 'time' === $format ? aj_get_time_formats() : aj_get_date_formats( $js );
$custom  = true;
$js      = isset( $js ) && 'date' === $format ? $js : false;

$data            = isset( $data ) ? $data : array();
$data['current'] = date_i18n( 'Y-m-d H:i:s' );
$data['js']      = ! ! $js ? 'yes' : 'no';
$data['format']  = $format;

$loop = 0;

wp_enqueue_script( 'aj-date-format' );
?>
<div class="<?php echo esc_attr( $class ); ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
	<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
>
	<?php foreach ( $options as $key => $label ) : ?>
		<?php
		$loop ++;
		$checked  = '';
		$radio_id = $field_id . '-' . $loop . '-' . sanitize_key( $key );
		if ( $value === $key ) { // checked() doesn't use strict comparison.
			$checked = " checked='checked'";
			$custom  = false;
		}
		?>
		<div class="aj-plugin-fw-radio__row">
			<input type="radio" id="<?php echo esc_attr( $radio_id ); ?>" name="<?php echo esc_attr( $name ); ?>"
					class="aj-plugin-fw-date-format__option"
					value="<?php echo esc_attr( $key ); ?>" <?php echo esc_html( $checked ); ?>
					data-preview="<?php echo esc_attr( date_i18n( $label ) ); ?>"
			/>
			<label for="<?php echo esc_attr( $radio_id ); ?>">
				<?php echo esc_html( date_i18n( $label ) ); ?>
				<code><?php echo esc_html( $key ); ?></code>
			</label>
		</div>
	<?php endforeach; ?>
	<?php $radio_id = $field_id . '-custom'; ?>
	<div class="aj-plugin-fw-radio__row">
		<input type="radio" id="<?php echo esc_attr( $radio_id ); ?>" name="<?php echo esc_attr( $name ); ?>"
				value="\c\u\s\t\o\m" <?php checked( $custom ); ?>
				data-format-custom="<?php echo esc_attr( $value ); ?>"
		/>
		<label for="<?php echo esc_attr( $radio_id ); ?>"> <?php esc_html_e( 'Custom:', 'aj-plugin-fw' ); ?></label>
		<input type="text" name="<?php echo esc_attr( $name . '_text' ); ?>"
				id="<?php echo esc_attr( $radio_id ); ?>_text" value="<?php echo esc_attr( $value ); ?>"
				class="small-text aj-date-format-custom"/>
		<p>
			<strong><?php esc_html_e( 'Preview:', 'aj-plugin-fw' ); ?></strong>
			<span class="example"><?php echo ! $js ? esc_html( date_i18n( $value ) ) : ''; ?></span>
			<span class="spinner"></span>
		</p>
	</div>
</div>
