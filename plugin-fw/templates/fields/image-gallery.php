<?php
/**
 * Template for displaying the image-gallery field
 *
 * @var array $field The field.
 * @package AJ\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $name, $value ) = aj_plugin_fw_extract( $field, 'id', 'name', 'value' );

$image_ids = ! empty( $value ) ? array_filter( explode( ',', $value ) ) : array();
?>
<div class="aj-plugin-fw-image-gallery">
	<ul id="<?php echo esc_attr( $field_id ); ?>-extra-images" class="slides-wrapper extra-images ui-sortable clearfix">
		<?php foreach ( $image_ids as $image_id ) : ?>
			<li class="image" data-attachment_id= <?php echo esc_attr( $image_id ); ?>>
				<a href="#">
					<?php
					if ( function_exists( 'dido_image' ) ) {
						dido_image( "id=$image_id&size=admin-post-type-thumbnails" );
					} else {
						echo wp_get_attachment_image( $image_id, array( 80, 80 ) );
					}
					?>
				</a>
				<ul class="actions">
					<li><a href="#" class="delete" title="<?php esc_attr_e( 'Delete image', 'aj-plugin-fw' ); ?>">x</a></li>
				</ul>
			</li>
		<?php endforeach; ?>
	</ul>
	<input type="button"
			id="<?php echo esc_attr( $field_id ); ?>-button"
			class="image-gallery-button button"
			data-choose="<?php esc_attr_e( 'Add Images to Gallery', 'aj-plugin-fw' ); ?>"
			data-update="<?php esc_attr_e( 'Add to gallery', 'aj-plugin-fw' ); ?>"
			value="<?php esc_attr_e( 'Add images', 'aj-plugin-fw' ); ?>"
			data-delete="<?php esc_attr_e( 'Delete image', 'aj-plugin-fw' ); ?>"
			data-text="<?php esc_attr_e( 'Delete', 'aj-plugin-fw' ); ?>"
	/>
	<input type="hidden" class="image_gallery_ids" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
</div>
