<?php
/**
 * Admin ask estimate email
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $wishlist_data       \AJ_WCWL_Wishlist
 * @var $email_heading       string
 * @var $email               \WC_Email
 * @var $user_formatted_name string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
	<?php
	// translators: 1. User name.
	echo esc_html( sprintf( __( 'You have received an estimate request from %s. The request is the following:', 'aj-woocommerce-wishlist' ), $user_formatted_name ) );
	?>
</p>

<?php do_action( 'aj_wcwl_email_before_wishlist_table', $wishlist_data ); ?>

<?php if ( $wishlist_data->get_token() ) : ?>
	<h2>
		<a href="<?php echo esc_url( $wishlist_data->get_url() ); ?>">
			<?php
			// translators: 1. Wishlist name.
			echo esc_html( sprintf( apply_filters( 'aj_wcwl_ask_estimate_email_wishlist_name', __( 'Wishlist: %s', 'aj-woocommerce-wishlist' ), $wishlist_data ), $wishlist_data->get_token() ) );
			?>
		</a>
	</h2>
<?php else : ?>
	<h2><?php esc_html_e( 'Wishlist:', 'aj-woocommerce-wishlist' ); ?></h2>
<?php endif; ?>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
	<tr>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Product', 'aj-woocommerce-wishlist' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Quantity', 'aj-woocommerce-wishlist' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Price', 'aj-woocommerce-wishlist' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ( $wishlist_data->has_items() ) :
		foreach ( $wishlist_data->get_items() as $item ) :
			$product = $item->get_product();
			?>
			<tr>
				<td scope="col" style="text-align:left;">
					<a href="<?php echo esc_url( get_edit_post_link( $product->get_id() ) ); ?>"><?php echo wp_kses_post( $product->get_title() ); ?></a>
					<?php
					if ( $product->is_type( 'variation' ) ) {
						echo wc_get_formatted_variation( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</td>
				<td scope="col" style="text-align:left;">
					<?php echo esc_html( $item->get_quantity() ); ?>
				</td>
				<td scope="col" style="text-align:left;">
					<?php echo wc_price( $item->get_product_price() );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php
		endforeach;
	endif;
	?>
	</tbody>
</table>

<?php if ( ! empty( $additional_notes ) ) : ?>
	<h2><?php esc_html_e( 'Additional info:', 'aj-woocommerce-wishlist' ); ?></h2>
	<p>
		<?php echo esc_html( $additional_notes ); ?>
	</p>

<?php endif; ?>

<?php if ( ! empty( $additional_data ) ) : ?>
	<h2><?php esc_html_e( 'Additional data:', 'aj-woocommerce-wishlist' ); ?></h2>
	<p>
		<?php foreach ( $additional_data as $key => $value ) : ?>

			<?php
			$key   = strip_tags( ucwords( str_replace( array( '_', '-' ), ' ', $key ) ) );
			$value = strip_tags( $value );
			?>

			<b><?php echo esc_html( $key ); ?></b>: <?php echo esc_html( $value ); ?><br/>

		<?php endforeach; ?>
	</p>

<?php endif; ?>

<?php do_action( 'aj_wcwl_email_after_wishlist_table', $wishlist_data ); ?>

<h2><?php esc_html_e( 'Customer details', 'aj-woocommerce-wishlist' ); ?></h2>

<p>
	<b><?php esc_html_e( 'Email:', 'aj-woocommerce-wishlist' ); ?></b>
	<a href="mailto:<?php echo esc_url( $email->reply_email ); ?>">
		<?php echo esc_html( $email->reply_email ); ?></a>
</p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
