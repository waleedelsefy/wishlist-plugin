<?php
/**
 * Promotion wizard popup
 *
 * This template is meant to be used using WCBackboneModal script
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $email_obj \WC_Email
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<script type="text/template" id="tmpl-aj-wcwl-promotion-wizard">
	<div class="wc-backbone-modal aj-wcwl-wizard-modal">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text">Close modal panel</span>
					</button>
				</header>
				<article>
					<form action="<?php echo esc_url_raw( add_query_arg( 'action', 'send_promotion', admin_url( 'admin.php' ) ) ); ?>" method="post">
						<div class="step step-1">
							<div class="col col-sx">
								<h2><?php esc_html_e( 'Set a promotional e-mail', 'aj-woocommerce-wishlist' ); ?></h2>

								<div class="content-tabs">
									<ul class="tabs" role="tablist">
										<li><a href="" class="active" data-target="#content_html_tab" role="tab" aria-selected="true" aria-controls="content_html" data-template="html"><?php esc_html_e( 'E-mail HTML content', 'aj-woocommerce-wishlist' ); ?></a></li>
										<li><a href="" class="" data-target="#content_text_tab" role="tab" aria-selected="false" aria-controls="content_text" data-template="plain"><?php esc_html_e( 'E-mail Text content', 'aj-woocommerce-wishlist' ); ?></a></li>
									</ul>
									<div class="tab active" id="content_html_tab" role="tabpanel" aria-expanded="true">
										<textarea name="content_html" id="content_html" rows="10" class="with-editor"><?php echo wp_kses_post( $email_obj->get_option( 'content_html' ) ); ?></textarea>

										<p class="description">
											<?php esc_html_e( 'This field lets you modify the main content of the HTML version of the email.', 'aj-woocommerce-wishlist' ); ?>
										</p>

										<h4>
											<?php esc_html_e( 'You can use the following placeholder:', 'aj-woocommerce-wishlist' ); ?>
										</h4>

										<p class="placeholders">
											<?php echo $email_obj->get_placeholder_text( 'html' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</p>

										<small>
											<b class="alert"><?php esc_html_e( 'Note: ', 'aj-woocommerce-wishlist' ); ?></b>
											<?php esc_html_e( 'you can customize the header in WooCommerce &rsaquo; Settings &rsaquo; Emails.', 'aj-woocommerce-wishlist' ); ?>
										</small>
									</div>
									<div class="tab" id="content_text_tab" role="tabpanel" aria-expanded="false">
										<textarea name="content_text" id="content_text" rows="10"><?php echo esc_html( $email_obj->get_option( 'content_text' ) ); ?></textarea>

										<p class="description">
											<?php esc_html_e( 'This field lets you modify the main content of the text version of the email', 'aj-woocommerce-wishlist' ); ?>
										</p>

										<h4>
											<?php esc_html_e( 'You can use the following placeholder:', 'aj-woocommerce-wishlist' ); ?>
										</h4>

										<p class="placeholders">
											<?php echo $email_obj->get_placeholder_text( 'plain' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</p>

										<small>
											<b class="alert"><?php esc_html_e( 'Note: ', 'aj-woocommerce-wishlist' ); ?></b>
											<?php esc_html_e( 'you can customize the header in WooCommerce &rsaquo; Settings &rsaquo; Emails.', 'aj-woocommerce-wishlist' ); ?>
										</small>
									</div>
								</div>

								<div class="form-row">
									<label for="coupon"><?php esc_html_e( 'Coupon', 'aj-woocommerce-wishlist' ); ?></label>

									<select type="text" name="coupon" id="coupon" class="wc-product-search" data-placeholder="<?php esc_attr_e( 'Select a coupon', 'aj-woocommerce-wishlist' ); ?>" data-action="json_search_coupons">
									</select>

									<p class="description">
										<?php esc_html_e( 'This field lets you choose coupon to use for the email.', 'aj-woocommerce-wishlist' ); ?>
									</p>
								</div>
							</div><div class="col col-dx">
								<h4><?php esc_html_e( 'Preview:', 'aj-woocommerce-wishlist' ); ?></h4>
								<div class="email-preview">
									<div class="no-interactions">
									{{data.preview}}
									</div>
								</div>
								<div class="promotion-actions">
									<input name="save_draft" type="submit" class="save-promotion-draft" value="<?php esc_html_e( 'Save draft &rsaquo;', 'aj-woocommerce-wishlist' ); ?>">
									<a href="#" class="button-primary continue-button"><?php esc_html_e( 'Continue', 'aj-woocommerce-wishlist' ); ?></a>
								</div>
							</div>
						</div>
						<div class="step step-2">
							<div class="col">
								<a href="#" class="back-button"><?php esc_html_e( '&lsaquo; Back', 'aj-woocommerce-wishlist' ); ?></a>
								<h2><?php esc_html_e( 'Ready to send?', 'aj-woocommerce-wishlist' ); ?></h2>
								<p>
									<?php esc_html_e( "You're about to send this promotional email to", 'aj-woocommerce-wishlist' ); ?>
									<span class='receivers-count'><?php esc_html_e( '(calculating...) users', 'aj-woocommerce-wishlist' ); ?></span>.
								</p>
								<p class="show-on-long-queue" data-threshold="<?php echo esc_attr( apply_filters( 'aj_wcwl_back_in_stock_execution_limit', 20 ) ); ?>">
									<?php
									$message = sprintf(
										// translators: 1. Number of emails sent per hours.
										__(
											'In order to avoid overloading your server, we will send %d emails every hour. This will take some time; please relax and wait for the operation to complete ;)',
											'aj-woocommerce-wishlist'
										),
										apply_filters( 'aj_wcwl_back_in_stock_execution_limit', 20 )
									);
									echo esc_html( $message );
									?>
								</p>
								<button id="main_submit_button" class="button-primary">
									<i class="material-icons">send</i>
									<?php esc_html_e( 'Send email', 'aj-woocommerce-wishlist' ); ?>
								</button>
							</div>
						</div>
						<input type="hidden" name="product_id[]" value="{{data.product_id}}">
						<input type="hidden" name="user_id[]" value="{{data.user_id}}">
						<input type="hidden" id="template" name="template" value="html">
						<?php wp_nonce_field( 'send_promotion_email_action', 'send_promotion_email' ); ?>
					</form>
				</article>
			</section>
		</div>
	</div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>
