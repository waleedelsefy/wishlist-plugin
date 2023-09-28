<?php
/**
 * AJ Dashboard Class
 * handle WordPress Admin Dashboard
 *
 * @class   AJ_Dashboard
 * @package AJ\PluginFramework\Classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'AJ_Dashboard' ) ) {
	/**
	 * AJ_Dashboard class.
	 */
	class AJ_Dashboard {
		/**
		 * Products Feed URL
		 *
		 * @var string
		 */
		private static $products_feed = 'https://ajemes.com/latest-updates/feeds/';

		/**
		 * Blog Feed URL
		 *
		 * @var string
		 */
		private static $blog_feed = 'https://ajemes.com/feed/';

		/**
		 * Dashboard widget setup.
		 */
		public static function dashboard_widget_setup() {
			wp_add_dashboard_widget( 'aj_dashboard_products_news', __( 'AJ Latest Updates', 'aj-plugin-fw' ), 'AJ_Dashboard::dashboard_products_news' );
			wp_add_dashboard_widget( 'aj_dashboard_blog_news', __( 'Latest news from AJ Blog', 'aj-plugin-fw' ), 'AJ_Dashboard::dashboard_blog_news' );
		}


		/**
		 * Product news Widget
		 */
		public static function dashboard_products_news() {
			$items = 10;
			$rss   = static::$products_feed;
			if ( is_string( $rss ) ) {
				$rss = fetch_feed( $rss );
			} elseif ( is_array( $rss ) && isset( $rss['url'] ) ) {
				$rss = fetch_feed( $rss['url'] );
			} elseif ( ! is_object( $rss ) ) {
				return;
			}

			if ( is_wp_error( $rss ) ) {
				if ( is_admin() || current_user_can( 'manage_options' ) ) {
					echo '<p><strong>' . esc_html__( 'RSS Error:', 'aj-plugin-fw' ) . '</strong> ' . wp_kses_post( $rss->get_error_message() ) . '</p>';
				}

				return;
			}

			if ( ! $rss->get_item_quantity() ) {
				echo '<ul><li>' . esc_html__( 'An error has occurred, which probably means the feed is down. Try again later.', 'aj-plugin-fw' ) . '</li></ul>';
				$rss->__destruct();
				unset( $rss );

				return;
			}

			/**
			 * The feed items.
			 *
			 * @var SimplePie_Item[] $last_updates
			 */
			$last_updates = $rss->get_items( 0, $items );
			$html_classes = 'rsswidget aj-update-feeds';
			$output       = '';

			if ( count( $last_updates ) > 0 ) {
				$output = '<ul class="aj-update-feeds">';
			}

			foreach ( $last_updates as $last_update ) {
				$output .= '<li class="aj-update-feed">';

				$date      = $last_update->get_date( 'U' );
				$date_i18n = ! empty( $date ) ? date_i18n( get_option( 'date_format' ), $date ) : '';
				$html_date = ! empty( $date_i18n ) ? ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>' : '';

				$output .= sprintf( '<a target="_blank" href="%s" class="%s">%s</a> %s', $last_update->get_permalink(), $html_classes, $last_update->get_title(), $html_date );

				$changelog = $last_update->get_description();

				if ( ! empty( $changelog ) ) {
					$output .= ' - ';
					$output .= sprintf( '<a class="aj-last-changelog" href="#" data-changelogid="%s" data-plugininfo="%s">%s</a>', $last_update->get_id( true ), $last_update->get_title(), _x( 'View Changelog', 'Plugin FW', 'aj-plugin-fw' ) );
					$output .= sprintf( '<div style="display: none;" id="%s"><div style="display: table;"><img class="aj-feeds-logo" src="%s" /><h3 class="aj-feeds-plugin-name"><span style="font-weight: normal;">%s</span> %s</h3></div><p>%s</p></div>', $last_update->get_id( true ), aj_plugin_fw_get_default_logo(), _x( 'Latest update released on', 'Plugin FW', 'aj-plugin-fw' ), $date_i18n, $changelog );
				}

				$output .= '</li>';
			}

			if ( ! empty( $output ) ) {
				$output .= '</ul>';
			}

			echo wp_kses_post( $output );
			$rss->__destruct();
			unset( $rss );
		}

		/**
		 * Blog news Widget
		 */
		public static function dashboard_blog_news() {
			$args = array(
				'show_author'  => 0,
				'show_date'    => 1,
				'show_summary' => 1,
				'items'        => 3,
			);
			$feed = static::$blog_feed;
			wp_widget_rss_output( $feed, $args );
		}

		/**
		 * Enqueue Styles and Scripts for View Last Changelog widget
		 */
		public static function enqueue_scripts() {
			if ( function_exists( 'get_current_screen' ) && get_current_screen() && 'dashboard' === get_current_screen()->id ) {
				$script_path = defined( 'DIDO_CORE_PLUGIN_URL' ) ? DIDO_CORE_PLUGIN_URL : get_template_directory_uri() . '/core/plugin-fw';
				$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				wp_enqueue_script( 'aj-dashboard', $script_path . '/assets/js/aj-dashboard' . $suffix . '.js', array( 'jquery-ui-dialog' ), aj_plugin_fw_get_version(), true );
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				$l10n = array(
					'buttons' => array(
						'close' => _x( 'Close', 'Button label', 'aj-plugin-fw' ),
					),
				);
				wp_localize_script( 'aj-dashboard', 'aj_dashboard', $l10n );
			}
		}
	}

	if ( apply_filters( 'aj_plugin_fw_show_dashboard_widgets', true ) ) {
		add_action( 'wp_dashboard_setup', 'AJ_Dashboard::dashboard_widget_setup' );
		add_action( 'admin_enqueue_scripts', 'AJ_Dashboard::enqueue_scripts', 20 );
	}
}
