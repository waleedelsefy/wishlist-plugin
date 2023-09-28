<?php
/**
 * Template for displaying the sidebars field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $name, $value ) = aj_plugin_fw_extract( $field, 'id', 'name', 'value' );

$layout        = ! isset( $value['layout'] ) ? 'sidebar-no' : $value['layout'];
$sidebar_left  = ! isset( $value['sidebar-left'] ) ? '-1' : $value['sidebar-left'];
$sidebar_right = ! isset( $value['sidebar-right'] ) ? '-1' : $value['sidebar-right'];
?>
<div class="aj-plugin-fw-sidebar-layout">
	<div class="option">
		<input type="radio" name="<?php echo esc_attr( $name ); ?>[layout]" id="<?php echo esc_attr( $field_id ) . '-left'; ?>" value="sidebar-left" <?php checked( $layout, 'sidebar-left' ); ?> />
		<img src="<?php echo esc_url( DIDO_CORE_PLUGIN_URL ); ?>/assets/images/sidebar-left.png" title="<?php esc_attr_e( 'Left sidebar', 'aj-plugin-fw' ); ?>" alt="<?php esc_attr_e( 'Left sidebar', 'aj-plugin-fw' ); ?>" class="<?php echo esc_attr( $field_id ) . '-left'; ?>" data-type="left"/>

		<input type="radio" name="<?php echo esc_attr( $name ); ?>[layout]" id="<?php echo esc_attr( $field_id ) . '-right'; ?>" value="sidebar-right" <?php checked( $layout, 'sidebar-right' ); ?> />
		<img src="<?php echo esc_url( DIDO_CORE_PLUGIN_URL ); ?>/assets/images/sidebar-right.png" title="<?php esc_attr_e( 'Right sidebar', 'aj-plugin-fw' ); ?>" alt="<?php esc_attr_e( 'Right sidebar', 'aj-plugin-fw' ); ?>" class="<?php echo esc_attr( $field_id ) . '-right'; ?>" data-type="right"/>

		<input type="radio" name="<?php echo esc_attr( $name ); ?>[layout]" id="<?php echo esc_attr( $field_id ) . '-double'; ?>" value="sidebar-double" <?php checked( $layout, 'sidebar-double' ); ?> />
		<img src="<?php echo esc_url( DIDO_CORE_PLUGIN_URL ); ?>/assets/images/double-sidebar.png" title="<?php esc_attr_e( 'No sidebar', 'aj-plugin-fw' ); ?>" alt="<?php esc_attr_e( 'No sidebar', 'aj-plugin-fw' ); ?>" class="<?php echo esc_attr( $field_id ) . '-double'; ?>" data-type="double"/>

		<input type="radio" name="<?php echo esc_attr( $name ); ?>[layout]" id="<?php echo esc_attr( $field_id ) . '-no'; ?>" value="sidebar-no" <?php checked( $layout, 'sidebar-no' ); ?> />
		<img src="<?php echo esc_url( DIDO_CORE_PLUGIN_URL ); ?>/assets/images/no-sidebar.png" title="<?php esc_attr_e( 'No sidebar', 'aj-plugin-fw' ); ?>" alt="<?php esc_attr_e( 'No sidebar', 'aj-plugin-fw' ); ?>" class="<?php echo esc_attr( $field_id ) . '-no'; ?>" data-type="none"/>
	</div>
	<div class="clearfix"></div>
	<div class="option" id="choose-sidebars">
		<div class="side">
			<div class="aj-plugin-fw-sidebar-layout-sidebar-left-container select-mask"
				<?php if ( ! in_array( $layout, array( 'sidebar-double', 'sidebar-left' ), true ) ) : ?>
					style="display:none"
				<?php endif; ?>
			>
				<label for="<?php echo esc_attr( $field_id ); ?>-sidebar-left"><?php esc_html_e( 'Left Sidebar', 'aj-plugin-fw' ); ?></label>
				<select class="aj-plugin-fw-select" name="<?php echo esc_attr( $name ); ?>[sidebar-left]" id="<?php echo esc_attr( $field_id ); ?>-sidebar-left">
					<option value="-1"><?php esc_html_e( 'Choose a sidebar', 'aj-plugin-fw' ); ?></option>
					<?php foreach ( dido_registered_sidebars() as $val => $option ) { ?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $sidebar_left, $val ); ?>><?php echo esc_html( $option ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="side" style="clear: both">
			<div class="aj-plugin-fw-sidebar-layout-sidebar-right-container select-mask"
				<?php if ( ! in_array( $layout, array( 'sidebar-double', 'sidebar-right' ), true ) ) : ?>
					style="display:none"
				<?php endif; ?>
			>
				<label for="<?php echo esc_attr( $field_id ); ?>-sidebar-right"><?php esc_html_e( 'Right Sidebar', 'aj-plugin-fw' ); ?></label>
				<select class="aj-plugin-fw-select" name="<?php echo esc_attr( $name ); ?>[sidebar-right]" id="<?php echo esc_attr( $field_id ); ?>-sidebar-right">
					<option value="-1"><?php esc_html_e( 'Choose a sidebar', 'aj-plugin-fw' ); ?></option>
					<?php foreach ( dido_registered_sidebars() as $val => $option ) { ?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $sidebar_right, $val ); ?>><?php echo esc_html( $option ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>
