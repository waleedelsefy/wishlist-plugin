<?php
/**
 * Plugin Name: AJ WooCommerce Wishlist
 * Plugin URI:https://dido.pro/plugins/aj-woocommerce-wishlist/
 * Description:AJ WooCommerce Wishlist
 * Version: 0.1.26
 * Author: waleed elsefy
 * Author URI:https://dido.pro
 * Text Domain: aj-woocommerce-wishlist
 * Domain Path: /languages/
 * WC requires at least: 4.2.0
 * WC tested up to: 5.1
 *
 * @author waleed elsefy
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Copyright 2020  Your Inspiration Solutions (email : plugins@ajemes.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'aj_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/dido-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'aj_plugin_registration_hook' );

if ( ! defined( 'AJ_WCWL' ) ) {
	define( 'AJ_WCWL', true );
}

if ( ! defined( 'AJ_WCWL_URL' ) ) {
	define( 'AJ_WCWL_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'AJ_WCWL_DIR' ) ) {
	define( 'AJ_WCWL_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'AJ_WCWL_INC' ) ) {
	define( 'AJ_WCWL_INC', AJ_WCWL_DIR . 'includes/' );
}

if ( ! defined( 'AJ_WCWL_INIT' ) ) {
	define( 'AJ_WCWL_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'AJ_WCWL_SLUG' ) ) {
	define( 'AJ_WCWL_SLUG', 'aj-woocommerce-wishlist' );
}

if ( ! defined( 'AJ_WCWL_SECRET_KEY' ) ) {
	define( 'AJ_WCWL_SECRET_KEY', 'ky18RdyseqSoSPgdungS' );
}

if ( ! defined( 'AJ_WCWL_PREMIUM' ) ) {
	define( 'AJ_WCWL_PREMIUM', '1' );
}

if ( ! defined( 'AJ_WCWL_PREMIUM_INIT' ) ) {
	define( 'AJ_WCWL_PREMIUM_INIT', plugin_basename( __FILE__ ) );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'dido_maybe_plugin_fw_loader' ) && file_exists( AJ_WCWL_DIR . 'plugin-fw/init.php' ) ) {
	require_once( AJ_WCWL_DIR . 'plugin-fw/init.php' );
}
dido_maybe_plugin_fw_loader( AJ_WCWL_DIR );

if ( ! function_exists( 'aj_wishlist_constructor' ) ) {
	/**
	 * Bootstrap function; loads all required dependencies and start the process
	 *
	 * @return void
	 * @since 2.0.0
	 */
	function aj_wishlist_constructor() {

		load_plugin_textdomain( 'aj-woocommerce-wishlist', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load required classes and functions.
		require_once( AJ_WCWL_INC . 'data-stores/class.aj-wcwl-wishlist-data-store.php' );
		require_once( AJ_WCWL_INC . 'data-stores/class.aj-wcwl-wishlist-item-data-store.php' );
		require_once( AJ_WCWL_INC . 'functions.aj-wcwl.php' );
		require_once( AJ_WCWL_INC . 'legacy/functions.aj-wcwl-legacy.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-exception.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-form-handler.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-form-handler-premium.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-ajax-handler.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-ajax-handler-premium.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-session.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-cron.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-cron-premium.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-wishlist.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-wishlist-item.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-wishlist-factory.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-premium.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-frontend.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-frontend-premium.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-install.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-shortcode.php' );
		require_once( AJ_WCWL_INC . 'class.aj-wcwl-shortcode-premium.php' );

		// load widget classes.
		require_once( AJ_WCWL_INC . 'widgets/class.aj-wcwl-widget.php' );
		require_once( AJ_WCWL_INC . 'widgets/class.aj-wcwl-items-widget.php' );

		// load admin classes.
		if ( is_admin() ) {
			require_once( AJ_WCWL_INC . 'class.aj-wcwl-admin.php' );
			require_once( AJ_WCWL_INC . 'class.aj-wcwl-admin-premium.php' );
		}

		// Let's start the game!

		/**
		 * $aj_wcwl global was deprecated since 3.0.0
		 *
		 * @deprecated
		 */
		global $aj_wcwl;
		$aj_wcwl = AJ_WCWL_Premium();
	}
}
add_action( 'aj_wcwl_init', 'aj_wishlist_constructor' );

if ( ! function_exists( 'aj_wishlist_install' ) ) {
	/**
	 * Performs pre-flight checks, and gives green light for plugin bootstrap
	 *
	 * @return void
	 * @since 2.0.0
	 */
	function aj_wishlist_install() {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( ! function_exists( 'dido_deactive_free_version' ) ) {
			require_once 'plugin-fw/dido-deactive-plugin.php';
		}
		dido_deactive_free_version( 'AJ_WCWL_FREE_INIT', plugin_basename( __FILE__ ) );

		if ( function_exists( 'aj_deactive_jetpack_module' ) ) {
			global $aj_jetpack_1;
			aj_deactive_jetpack_module( $aj_jetpack_1, 'AJ_WCWL_PREMIUM_INIT', plugin_basename( __FILE__ ) );
		}

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'aj_wcwl_install_woocommerce_admin_notice' );
		} else {
			do_action( 'aj_wcwl_init' );
		}
	}
}
add_action( 'plugins_loaded', 'aj_wishlist_install', 11 );

if ( ! function_exists( 'aj_wcwl_install_woocommerce_admin_notice' ) ) {
	/**
	 * Shows admin notice when plugin is activated without WooCommerce
	 *
	 * @return void
	 * @since 2.0.0
	 */
	function aj_wcwl_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p><?php echo esc_html( 'AJ WooCommerce Wishlist ' . __( 'is enabled but not effective. It requires WooCommerce to work.', 'aj-woocommerce-wishlist' ) ); ?></p>
		</div>
		<?php
	}
}
