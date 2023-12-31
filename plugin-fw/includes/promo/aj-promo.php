<?php
/**
 * AJ Promo functions
 * handle the AJ promotions
 *
 * @package AJ\PluginFramework
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'simplexml_load_string' ) ) {
	return false;
}

add_action( 'admin_notices', 'aj_plugin_fw_regenerate_transient' );
add_action( 'admin_notices', 'aj_plugin_fw_promo_notices', 15 );
add_action( 'admin_enqueue_scripts', 'aj_plugin_fw_notice_dismiss', 20 );

if ( ! function_exists( 'aj_plugin_fw_promo_notices' ) ) {
	/**
	 * Add promo notices.
	 */
	function aj_plugin_fw_promo_notices() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		global $pagenow;
		$not_administrator = function_exists( 'current_user_can' ) && ! current_user_can( 'administrator' );
		$is_dashboard      = 'index.php' === $pagenow;
		$is_plugin_page    = 'plugins.php' === $pagenow || 'plugin-install.php' === $pagenow && 'plugin-editor.php' === $pagenow;
		$wc_post_types     = array( 'shop_order', 'shop_coupon' );
		$is_wc_post_types  = isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], $wc_post_types, true );
		$wc_pages          = array( 'wc-reports', 'wc-settings', 'wc-status', 'wc-addons' );
		$page              = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : false;
		$is_wc_pages       = $page && in_array( $page, $wc_pages, true );
		$is_aj_page      = $page && false !== strstr( $page, 'aj' );

		if ( $not_administrator ) {
			return;
		}

		if ( ! $is_plugin_page && ! $is_wc_pages && ! $is_wc_post_types && ! $is_aj_page ) {
			return;
		}

		$base_url                   = apply_filters( 'aj_plugin_fw_promo_base_url', DIDO_CORE_PLUGIN_URL . '/includes/promo/' );
		$xml                        = apply_filters( 'aj_plugin_fw_promo_xml_url', DIDO_CORE_PLUGIN_PATH . '/includes/promo/aj-promo.xml' );
		$transient                  = 'aj_promo_message';
		$remote_data                = get_site_transient( $transient );
		$regenerate_promo_transient = isset( $_GET['aj_regenerate_promo_transient'] ) && 'yes' === $_GET['aj_regenerate_promo_transient'];
		$promo_data                 = false;
		$create_transient           = false;

		if ( false === $remote_data || apply_filters( 'aj_plugin_fw_force_regenerate_promo_transient', false ) || $regenerate_promo_transient ) {
			$remote_data      = file_get_contents( $xml ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$create_transient = true;
		}

		if ( ! is_wp_error( $remote_data ) && ! empty( $remote_data ) ) {
			$promo_data = @simplexml_load_string( $remote_data ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

			$is_membership_user = false;
			$license            = function_exists( 'AJ_Plugin_Licence' ) ? AJ_Plugin_Licence()->get_licence() : array();
			$xml_expiry_date    = '';

			if ( is_array( $license ) && apply_filters( 'aj_plugin_fw_check_for_membership_user', true ) ) {
				// Check if the user have the AJ Club.
				foreach ( $license as $plugin => $data ) {
					if ( ! empty( $data['is_membership'] ) ) {
						$is_membership_user = true;
						$xml_expiry_date    = $data['licence_expires'];
						$remote_data        = array();
						$promo_data         = array();
						$create_transient   = true;
						break;
					}
				}
			}

			if ( empty( $is_membership_user ) && ! empty( $promo_data->expiry_date ) ) {
				$xml_expiry_date = $promo_data->expiry_date;
			}

			if ( true === $create_transient ) {
				set_site_transient( $transient, $remote_data, aj_plugin_fw_get_promo_transient_expiry_date( $xml_expiry_date ) );
			}

			if ( $promo_data && ! empty( $promo_data->promo ) ) {
				$now = apply_filters( 'aj_plugin_fw_promo_now_date', strtotime( current_time( 'mysql' ) ) );

				foreach ( $promo_data->promo as $promo ) {
					$show_promo = true;
					// Check for Special Promo.
					if ( ! empty( $promo->show_promo_in ) ) {
						$show_promo_in = explode( ',', $promo->show_promo_in );
						$show_promo_in = array_map( 'trim', $show_promo_in );
						if ( ! empty( $show_promo_in ) ) {
							$show_promo = false;
							foreach ( $show_promo_in as $plugin ) {
								$plugin_slug         = constant( $plugin );
								$plugin_is_activated = ! empty( $license[ $plugin_slug ]['activated'] );
								if ( defined( $plugin ) && ! apply_filters( 'aj_plugin_fw_promo_plugin_is_activated', $plugin_is_activated ) ) {
									$show_promo = true;
									break;
								}
							}
						}
					}

					$start_date = isset( $promo->start_date ) ? $promo->start_date : '';
					$end_date   = isset( $promo->end_date ) ? $promo->end_date : '';

					if ( $show_promo && ! empty( $start_date ) && ! empty( $end_date ) ) {
						$start_date = strtotime( $start_date );
						$end_date   = strtotime( $end_date );

						if ( $end_date >= $start_date && $now >= $start_date && $now <= $end_date ) {
							// Is this a valid promo.
							$title            = isset( $promo->title ) ? $promo->title : '';
							$description      = isset( $promo->description ) ? $promo->description : '';
							$url              = isset( $promo->link->url ) ? $promo->link->url : '';
							$url_label        = isset( $promo->link->label ) ? $promo->link->label : '';
							$image_bg_color   = isset( $promo->style->image_bg_color ) ? $promo->style->image_bg_color : '';
							$border_color     = isset( $promo->style->border_color ) ? $promo->style->border_color : '';
							$background_color = isset( $promo->style->background_color ) ? $promo->style->background_color : '';
							$promo_id         = isset( $promo->promo_id ) ? $promo->promo_id : '';
							$banner           = isset( $promo->banner ) ? $promo->banner : '';
							$style            = '';
							$link             = '';
							$show_notice      = false;

							if ( ! empty( $border_color ) ) {
								$style .= "border-left-color: {$border_color};";
							}

							if ( ! empty( $background_color ) ) {
								$style .= "background-color: {$background_color};";
							}

							if ( ! empty( $image_bg_color ) ) {
								$image_bg_color = "background-color: {$image_bg_color};";
							}

							if ( ! empty( $title ) ) {
								$promo_id .= $title;

								$title       = sprintf( '%s: ', $title );
								$show_notice = true;
							}

							if ( ! empty( $description ) ) {
								$promo_id .= $description;

								$description = sprintf( '%s', $description );
								$show_notice = true;
							}

							if ( ! empty( $url ) && ! empty( $url_label ) ) {
								$promo_id .= $url . $url_label;

								$link        = sprintf( '<a href="%s" target="_blank">%s</a>', $url, $url_label );
								$show_notice = true;
							}

							if ( ! empty( $banner ) ) {
								$banner = sprintf( '<img src="%s" class="aj-promo-banner-image">', $base_url . $banner );

								if ( ! empty( $url ) ) {
									$banner = sprintf( '<a class="aj-promo-banner-image-link" href="%s" target="_blank" style="%s">%s</a>', $url, $image_bg_color, $banner );
								}
							}

							$unique_promo_id = 'aj-notice-' . md5( $promo_id );

							if ( ! empty( $_COOKIE[ 'hide_' . $unique_promo_id ] ) && 'yes' === $_COOKIE[ 'hide_' . $unique_promo_id ] ) {
								$show_notice = false;
							}

							if ( $show_notice ) : ?>
								<?php wp_enqueue_script( 'aj-promo' ); ?>
								<div id="<?php echo esc_attr( $unique_promo_id ); ?>" class="aj-notice-is-dismissible notice notice-aj notice-alt is-dismissible" style="<?php echo esc_attr( $style ); ?>" data-expiry= <?php echo esc_attr( $promo->end_date ); ?>>
									<p>
										<?php
										if ( ! ! $banner ) {
											echo wp_kses_post( $banner );
										}

										echo wp_kses_post( sprintf( '%s %s %s', $title, $description, $link ) );
										?>
									</p>
								</div>
							<?php endif; ?>
							<?php
						}
					}
				}
			}
		}

		// phpcs:enable
	}
}

if ( ! function_exists( 'aj_plugin_fw_notice_dismiss' ) ) {
	/**
	 * Dismiss the notice scripts.
	 */
	function aj_plugin_fw_notice_dismiss() {
		$script_path = defined( 'DIDO_CORE_PLUGIN_URL' ) ? DIDO_CORE_PLUGIN_URL : get_template_directory_uri() . '/core/plugin-fw';
		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'aj-promo', $script_path . '/assets/js/aj-promo' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
	}
}

if ( ! function_exists( 'aj_plugin_fw_get_promo_transient_expiry_date' ) ) {
	/**
	 * Retrieve the expiry date in integer.
	 *
	 * @param string $expiry_date The expiry date.
	 *
	 * @return false|int
	 */
	function aj_plugin_fw_get_promo_transient_expiry_date( $expiry_date ) {
		$xml_expiry_date = ! empty( $expiry_date ) ? $expiry_date : '+24 hours';
		$current         = strtotime( current_time( 'Y-m-d H:i:s' ) );
		$expiry_date     = strtotime( $xml_expiry_date, $current );

		if ( $expiry_date <= $current ) {
			$expiry_date = strtotime( '+24 hours', $current );
		}

		return $expiry_date;
	}
}

if ( ! function_exists( 'aj_plugin_fw_regenerate_transient' ) ) {
	/**
	 * Regenerate transients for promo.
	 */
	function aj_plugin_fw_regenerate_transient() {
		if ( false === get_option( 'aj_plugin_fw_promo_2019_bis', false ) ) {
			delete_option( 'aj_plugin_fw_promo_2019' );
			delete_site_transient( 'aj_promo_message' );
			update_option( 'aj_plugin_fw_promo_2019_bis', true );
		}
	}
}
