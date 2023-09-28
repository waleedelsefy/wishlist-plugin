<?php
/**
 * Add to wishlist button template - Move popup
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 3.0.12
 */

/**
 * Template variables:
 *
 * @var $base_url                  string Current page url
 * @var $lists                     AJ_WCWL_Wishlist[]
 * @var $show_exists               bool Whether to show Exists message or not
 * @var $show_count                bool Whether to show count of times item was added to wishlist
 * @var $show_view                 bool Whether to show view button or not
 * @var $exists                    bool Whether the product is already in list
 * @var $product_id                int Current product id
 * @var $parent_product_id         int Parent for current product
 * @var $already_in_wishslist_text string Already in wishlist message
 * @var $browse_wishlist_text      string Browse wishlist message
 * @var $wishlist_url              string View wishlist url
 * @var $link_classes              string Classes for the Add to Wishlist link
 * @var $link_popup_classes        string Classes for Open Add to Wishlist Popup link
 * @var $label_popup               string Label for Open Add to Wishlist Popup link
 * @var $popup_title               string Popup title
 * @var $product_image             string Product image url (not is use)
 * @var $found_in_list             \AJ_WCWL_Wishlist Wishlist were system found an occurrence of the product
 * @var $found_item                \AJ_WCWL_Wishlist_item Current wishlist item
 * @var $icon                      string Icon HTML tag
 * @var $heading_icon              string Heading icon HTML tag
 * @var $loop_position             string Loop position
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

$unique_id = mt_rand();
?>

<div class="aj-wcwl-add-button">
	<span class="feedback">
		<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo esc_html( apply_filters( 'aj_wcwl_in_your_wishlist_label', __( 'In your wishlist', 'aj-woocommerce-wishlist' ) ) ); ?>
	</span>

	<?php if( $show_view ): ?>
		<a href="<?php echo esc_url( $found_in_list->get_url() ); ?>" class="view-wishlist"><?php echo esc_html( apply_filters( 'aj_wcwl_view_wishlist_label', __( 'View &rsaquo;', 'aj-woocommerce-wishlist' ) ) ); ?></a>
		<span class="separator"><?php esc_html_e( 'or', 'aj-woocommerce-wishlist' ); ?></span>
	<?php endif; ?>

	<!-- WISHLIST POPUP OPENER -->
	<a href="#add_to_wishlist_popup_<?php echo esc_attr( $product_id ) ?>_<?php echo esc_attr( $unique_id ); ?>" rel="nofollow" class="move_to_another_wishlist <?php echo esc_attr( $link_classes ); ?> open-pretty-photo" data-rel="prettyPhoto[add_to_wishlist_<?php echo esc_attr( $product_id ); ?>_<?php echo esc_attr( $unique_id ); ?>]" data-title="<?php echo esc_attr( apply_filters( 'aj_wcwl_move_to_another_wishlist_title', __( 'Move to another wishlist', 'aj-woocommerce-list' ) ) ) ?>" >
		<?php echo ( ! $is_single && 'before_image' == $loop_position ) ? $icon : false; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo wp_kses_post( $label ); ?>
	</a>

	<!-- WISHLIST POPUP -->
	<div id="add_to_wishlist_popup_<?php echo esc_attr( $product_id ); ?>_<?php echo esc_attr( $unique_id ); ?>" class="aj-wcwl-popup">
		<form class="aj-wcwl-popup-form" method="post" action="<?php echo esc_url( add_query_arg( array( 'move_to_another_wishlist' => true, 'row_id' => $product_id ), $base_url ) ); ?>">
			<div class="aj-wcwl-popup-content">
				<?php if( apply_filters( 'aj_wcwl_show_popup_heading_icon_instead_of_title', ! empty( $heading_icon ), $heading_icon ) ): ?>
					<p class="heading-icon">
						<?php echo apply_filters( 'aj_wcwl_popup_heading_icon_class', $heading_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
					<p class="popup-description">
						<?php echo esc_html( $popup_title ) ?>
					</p>
				<?php else: ?>
					<h3><?php echo esc_html( $popup_title ); ?></h3>
				<?php endif; ?>

				<p class="form-row">
					<?php echo wp_kses_post( sprintf( __( 'This item is already in the <b>%s wishlist</b>.<br/>You can move it to another list:', 'aj-woocommerce-wishlist' ), $found_in_list->get_formatted_name() ) ); ?>
				</p>

				<div class="aj-wcwl-first-row">
					<div class="aj-wcwl-wishlist-select-container">
						<p class="form-row form-row-wide">
							<select name="new_wishlist_id" class="wishlist-select">
								<?php if( ! empty( $lists ) ): ?>
									<?php foreach( $lists as $list ):?>

										<?php
										if( $found_in_list->get_id() === $list->get_id() ){
											continue;
										}
										?>

										<option value="<?php echo esc_attr( $list->get_token() ); ?>"><?php echo esc_html( $list->get_formatted_name() ); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
								<option value="new"><?php echo esc_html( apply_filters( 'aj_wcwl_create_new_list_text', __( 'Create a new list', 'aj-woocommerce-wishlist' ) ) ); ?></option>
							</select>
						</p>
					</div>
				</div>

				<div class="aj-wcwl-second-row">
					<p class="form-row form-row-wide">
						<input name="wishlist_name" type="text" class="wishlist-name input-text" placeholder="<?php echo apply_filters( 'aj_wcwl_new_list_title_text', __( 'Wishlist name', 'aj-woocommerce-wishlist' ) ) ?>"/>
					</p>
					<p class="form-row form-row-wide">
						<label>
							<input type="radio"  name="wishlist_visibility" value="0" class="public-visibility wishlist-visibility" <?php checked( true ) ?> />
							<?php echo aj_wcwl_get_privacy_label( 0, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</label>
						<label>
							<input type="radio" name="wishlist_visibility" value="1" class="shared-visibility wishlist-visibility" />
							<?php echo aj_wcwl_get_privacy_label( 1, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</label>
						<label>
							<input type="radio" name="wishlist_visibility" value="2" class="private-visibility wishlist-visibility" />
							<?php echo aj_wcwl_get_privacy_label( 2, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</label>
					</p>
				</div>
			</div>

			<div class="aj-wcwl-popup-footer">
				<a rel="nofollow" class="wishlist-submit move_to_wishlist <?php echo esc_attr( $link_popup_classes ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-original-product-id="<?php echo esc_attr( $parent_product_id ); ?>" data-item-id="<?php echo $found_item ? esc_attr( $found_item->get_id() ) : ''; ?>" data-origin-wishlist-id="<?php echo $found_in_list ? esc_attr( $found_in_list->get_token() ) : ''; ?>">
					<?php echo apply_filters( 'aj_wcwl_move_label', __( 'Move', 'aj-woocommerce-wishlist' ) ) ?>
				</a>

				<div class="aj-wcwl-remove-button">
					<i class="fa fa-trash"></i>
					<?php esc_html_e( 'or', 'aj-woocommerce-wishlist' ); ?>
					<a href="#" class="delete_item" data-item-id="<?php echo esc_attr( $found_item->get_id() ) ?>" data-product-id="<?php echo esc_attr( $product_id ) ?>" ><?php esc_html_e( 'click to remove it', 'aj-woocommerce-wishlist' ); ?></a>
				</div>
			</div>

			<?php wp_nonce_field( 'move_to_another_wishlist', 'move_to_another_wishlist_nonce' ); ?>
		</form>
	</div>
</div>
