<?php
/**
 * Template for displaying the icons field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $name, $filter_icons, $std, $value ) = aj_plugin_fw_extract( $field, 'id', 'name', 'filter_icons', 'std', 'value' );

wp_enqueue_style( 'font-awesome' );

$filter_icons      = ! ! $filter_icons ? $filter_icons : '';
$default_icon_text = isset( $std ) ? $std : false;
$default_icon_data = DIDO_Icons()->get_icon_data( $default_icon_text, $filter_icons );

$current_icon_data = DIDO_Icons()->get_icon_data( $value, $filter_icons );
$current_icon_text = $value;

$dido_icons = DIDO_Icons()->get_icons( $filter_icons );
?>

<div id="dido-icons-manager-wrapper-<?php echo esc_attr( $field_id ); ?>" class="dido-icons-manager-wrapper">

	<div class="dido-icons-manager-text">
		<div class="dido-icons-manager-icon-preview"
			<?php echo $current_icon_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		></div>
		<input class="dido-icons-manager-icon-text" type="text"
				id="<?php echo esc_attr( $field_id ); ?>"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $current_icon_text ); ?>"
		/>
		<div class="clear"></div>
	</div>


	<div class="dido-icons-manager-list-wrapper">
		<ul class="dido-icons-manager-list">
			<?php foreach ( $dido_icons as $font => $icons ) : ?>
				<?php foreach ( $icons as $key => $icon_name ) : ?>
					<?php
					$data_icon  = str_replace( '\\', '&#x', $key );
					$icon_text  = $font . ':' . $icon_name;
					$icon_class = $icon_text === $current_icon_text ? 'active' : '';

					$icon_class .= $icon_text === $default_icon_text ? ' default' : '';
					?>
					<li class="<?php echo esc_attr( $icon_class ); ?>"
							data-font="<?php echo esc_attr( $font ); ?>"
							data-icon="<?php echo $data_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
							data-key="<?php echo esc_attr( $key ); ?>"
							data-name="<?php echo esc_attr( $icon_name ); ?>"></li>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="dido-icons-manager-actions">
		<?php if ( $default_icon_text ) : ?>
			<div class="dido-icons-manager-action-set-default button"><?php esc_html_e( 'Set Default', 'aj-plugin-fw' ); ?>
				<i class="dido-icons-manager-default-icon-preview" <?php echo esc_html( $default_icon_data ); ?>></i>
			</div>
		<?php endif ?>
	</div>
</div>
