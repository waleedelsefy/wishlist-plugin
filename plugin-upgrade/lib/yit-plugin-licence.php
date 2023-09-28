<?php
/**
 * This file belongs to the DIDO Plugin Framework.
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @author AJ
 * @package AJ License & Upgrade Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'AJ_Plugin_Licence' ) ) {
	/**
	 * DIDO Plugin Licence Panel
	 * Setting Page to Manage Plugins
	 *
	 * @class      AJ_Plugin_Licence
	 * @since      1.0
	 * @author     Andrea Grillo      <andrea.grillo@ajemes.com>
	 * @package    AJ
	 */
	class AJ_Plugin_Licence extends AJ_Licence {

		/**
		 * The settings require to add the submenu page "Activation"
		 *
		 * @since 1.0
		 * @var array
		 */
		protected $settings = array();

		/**
		 * The single instance of the class
		 *
		 * @since 1.0
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Option name
		 *
		 * @since 1.0
		 * @var string
		 */
		protected $licence_option = 'dido_plugin_licence_activation';

		/**
		 * The product type
		 *
		 * @since 1.0
		 * @var string
		 */
		protected $product_type = 'plugin';

		/**
		 * Constructor
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@ajemes.com>
		 */
		public function __construct() {
			parent::__construct();

			if ( ! is_admin() ) {
				return;
			}

			$this->settings = array(
				'parent_page' => 'aj_plugin_panel',
				'page_title'  => __( 'License Activation', 'aj-plugin-upgrade-fw' ),
				'menu_title'  => __( 'License Activation', 'aj-plugin-upgrade-fw' ),
				'capability'  => 'manage_options',
				'page'        => 'aj_plugins_activation',
			);
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 99 );
			add_action( "wp_ajax_aj_activate-{$this->product_type}", array( $this, 'activate' ) );
			add_action( "wp_ajax_aj_deactivate-{$this->product_type}", array( $this, 'deactivate' ) );
			add_action( "wp_ajax_aj_remove-{$this->product_type}", array( $this, 'deactivate' ) );
			add_action( "wp_ajax_aj_update_licence_information-{$this->product_type}", array( $this, 'update_licence_information' ) );
			add_action( 'dido_licence_after_check', 'aj_plugin_fw_force_regenerate_plugin_update_transient' );
			add_filter( 'extra_plugin_headers', array( $this, 'extra_plugin_headers' ) );
		}

		/**
		 * Get the activation licence url
		 *
		 * @author Francesco Licandro
		 * @return bool|string
		 */
		public function get_license_url() {
			return add_query_arg( array( 'page' => 'aj_plugins_activation' ), admin_url( 'admin.php' ) );
		}

		/**
		 * Main plugin Instance
		 *
		 * @static
		 * @since  1.0
		 * @author Andrea Grillo <andrea.grillo@ajemes.com>
		 * @return object Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add "Activation" submenu page under AJ Plugins
		 *
		 * @since  1.0
		 * @author Andrea Grillo <andrea.grillo@ajemes.com>
		 * @return void
		 */
		public function add_submenu_page() {
			$no_active_products = $this->get_no_active_licence_key();
			$expired_product    = ! empty( $no_active_products['106'] ) ? count( $no_active_products['106'] ) : 0;
			$bubble             = ! empty( $expired_product ) ? " <span data-count='{$expired_product}' id='aj-expired-license-count' class='awaiting-mod count-{$expired_product}'><span class='expired-count'>{$expired_product}</span></span>" : '';

			add_submenu_page(
				$this->settings['parent_page'],
				$this->settings['page_title'],
				$this->settings['menu_title'] . $bubble,
				$this->settings['capability'],
				$this->settings['page'],
				array( $this, 'show_activation_panel' )
			);
		}

		/**
		 * Premium plugin registration
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@ajemes.com>
		 * @param string $plugin_init The plugin init file.
		 * @param string $secret_key The product secret key.
		 * @param string $product_id The plugin slug (product_id).
		 * @return void
		 */
		public function register( $plugin_init, $secret_key, $product_id ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins                                = get_plugins();
			$plugins[ $plugin_init ]['secret_key']  = $secret_key;
			$plugins[ $plugin_init ]['product_id']  = $product_id;
			$plugins[ $plugin_init ]['marketplace'] = ! empty( $plugins[ $plugin_init ]['AJ Marketplace'] ) ? $plugins[ $plugin_init ]['AJ Marketplace'] : 'aj';
			$this->products[ $plugin_init ]         = $plugins[ $plugin_init ];
		}

		/**
		 * Get the product type
		 *
		 * @author Francesco Licandro
		 * @return string
		 */
		public function get_product_type() {
			return $this->product_type;
		}

		/**
		 * Get license activation URL
		 *
		 * @since 3.0.17
		 * @author Andrea Grillo <andrea.grillo@ajemes.com>
		 * @return string
		 */
		public static function get_license_activation_url( $plugin_slug = '' ) {
			$args = array( 'page' => 'aj_plugins_activation' );
			if( ! empty( $plugin_slug ) ){
				$args['plugin'] = $plugin_slug;
			}
			return add_query_arg( $args, admin_url( 'admin.php' ) );
		}

		/**
		 * Add Extra Headers for Marketplace
		 *
		 * @author Andrea Grillo <andrea.grillo@ajemes.com>
		 * @param array $headers An array of headers.
		 * @return array
		 */
		public function extra_plugin_headers( $headers ) {
			$headers[] = 'AJ Marketplace';

			return $headers;
		}
	}
}

if ( ! function_exists( 'AJ_Plugin_Licence' ) ) {
	/**
	 * Get the main instance of class
	 *
	 * @since  1.0
	 * @author Francesco Licandro
	 * @return AJ_Plugin_Licence
	 */
	function AJ_Plugin_Licence() { // phpcs:ignore
		return AJ_Plugin_Licence::instance();
	}
}
