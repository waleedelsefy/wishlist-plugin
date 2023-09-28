<?php
/**
 * Template for displaying the dimensions field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$default_options = array(
	'dimensions'   => array(
		'top'    => _x( 'Top', 'Position in the "Dimensions" field', 'aj-plugin-fw' ),
		'right'  => _x( 'Right', 'Position in the "Dimensions" field', 'aj-plugin-fw' ),
		'bottom' => _x( 'Bottom', 'Position in the "Dimensions" field', 'aj-plugin-fw' ),
		'left'   => _x( 'Left', 'Position in the "Dimensions" field', 'aj-plugin-fw' ),
	),
	'units'        => array(
		'px'         => 'px',
		'percentage' => '%',
	),
	'allow_linked' => true,
	'min'          => false,
	'max'          => false,
);

$field = wp_parse_args( $field, $default_options );

list ( $field_id, $class, $name, $dimensions, $units, $allow_linked, $min, $max, $value, $data, $custom_attributes ) = aj_plugin_fw_extract( $field, 'id', 'class', 'name', 'dimensions', 'units', 'allow_linked', 'min', 'max', 'value', 'data', 'custom_attributes' );

$class = isset( $class ) ? $class : '';
$class = 'aj-plugin-fw-dimensions ' . $class;

$value = ! empty( $value ) ? $value : array();

$unit_value        = isset( $value['unit'] ) ? $value['unit'] : current( array_keys( $units ) );
$dimensions_values = isset( $value['dimensions'] ) ? $value['dimensions'] : array();
$linked            = isset( $value['linked'] ) ? $value['linked'] : 'yes';

if ( $allow_linked && 'yes' === $linked ) {
	$class .= ' aj-plugin-fw-dimensions--linked-active';
}
?>
<div id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo esc_attr( $class ); ?>"
	<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo isset( $data ) ? aj_plugin_fw_html_data_to_string( $data ) : ''; ?>
>
	<div class="aj-plugin-fw-dimensions__dimensions">
		<?php foreach ( $dimensions as $key => $dimension ) : ?>
			<?php
			$d_key        = sanitize_title( $key );
			$d_id         = "{$field_id}-dimension-{$d_key}";
			$d_name       = "{$name}[dimensions][{$d_key}]";
			$d_value      = isset( $dimensions_values[ $key ] ) ? $dimensions_values[ $key ] : 0;
			$d_attributes = '';
			$d_label      = $dimension;
			$d_min        = $min;
			$d_max        = $max;

			if ( is_array( $dimension ) ) {
				$d_label = isset( $dimension['label'] ) ? $dimension['label'] : $key;
				if ( isset( $dimension['custom_attributes'] ) ) {
					$d_attributes .= $dimension['custom_attributes'];
				}
				$d_min = isset( $dimension['min'] ) ? $dimension['min'] : $d_min;
				$d_max = isset( $dimension['max'] ) ? $dimension['max'] : $d_max;
			}

			if ( false !== $d_max ) {
				$d_attributes = " max={$d_max} " . $d_attributes;
			}

			if ( false !== $d_min ) {
				$d_attributes = " min={$d_min} " . $d_attributes;
			}

			?>
			<div class="aj-plugin-fw-dimensions__dimension aj-plugin-fw-dimensions__dimension--<?php echo esc_attr( $d_key ); ?>">
				<label for="<?php echo esc_attr( $d_id ); ?>" class="aj-plugin-fw-dimensions__dimension__label"><?php echo esc_html( $d_label ); ?></label>
				<input id="<?php echo esc_attr( $d_id ); ?>" class="aj-plugin-fw-dimensions__dimension__number"
						type="number" name="<?php echo esc_attr( $d_name ); ?>" value="<?php echo esc_attr( $d_value ); ?>"
					<?php if ( false !== $d_max ) : ?>
						max="<?php echo esc_attr( $d_max ); ?>"
					<?php endif; ?>
					<?php if ( false !== $d_min ) : ?>
						min="<?php echo esc_attr( $d_min ); ?>"
					<?php endif; ?>
					<?php echo $d_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				>
			</div>
		<?php endforeach ?>

		<?php if ( $allow_linked ) : ?>
			<div class="aj-plugin-fw-dimensions__linked" title="<?php echo esc_attr_x( 'Link values together', 'Tooltip in the "Dimensions" field', 'aj-plugin-fw' ); ?>">
				<input class='aj-plugin-fw-dimensions__linked__value' type="hidden" name="<?php echo esc_attr( $name ); ?>[linked]" value="<?php echo esc_attr( $linked ); ?>">
				<span class="dashicons dashicons-admin-links"></span>
			</div>
		<?php endif; ?>
	</div>
	<div class="aj-plugin-fw-dimensions__units">
		<input class='aj-plugin-fw-dimensions__unit__value' type="hidden" name="<?php echo esc_attr( $name ); ?>[unit]" value="<?php echo esc_attr( $unit_value ); ?>">
		<?php foreach ( $units as $key => $label ) : ?>
			<?php
			$key     = sanitize_title( $key );
			$classes = array(
				'aj-plugin-fw-dimensions__unit',
				"aj-plugin-fw-dimensions__unit--{$key}-unit",
			);
			if ( $unit_value === $key ) {
				$classes[] = 'aj-plugin-fw-dimensions__unit--selected';
			}

			if ( count( $units ) < 2 ) {
				$classes[] = 'aj-plugin-fw-dimensions__unit--unique';
			}

			$classes = implode( ' ', $classes );
			?>
			<span class="<?php echo esc_attr( $classes ); ?>" data-value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></span>
		<?php endforeach ?>
	</div>
</div>
