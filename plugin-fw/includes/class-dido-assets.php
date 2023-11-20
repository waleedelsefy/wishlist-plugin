<?php
/**
 * AJ Assets Class. Assets Handler.
 *
 * @class      DIDO_Assets
 * @package    AJ\PluginFramework\Classes
 * @since      3.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'DIDO_Assets' ) ) {
	/**
	 * DIDO_Assets class.
	 *
	 * @author     Leanza Francesco <leanzafrancesco@gmail.com>
	 */
	class DIDO_Assets {
		/**
		 * The framework version
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * The single instance of the class.
		 *
		 * @var DIDO_Assets
		 */
		private static $instance;

		/**
		 * Singleton implementation.
		 *
		 * @return DIDO_Assets
		 */
		public static function instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * DIDO_Assets constructor.
		 */
		private function __construct() {
			$this->version = aj_plugin_fw_get_version();
			add_action( 'admin_enqueue_scripts', array( $this, 'register_styles_and_scripts' ) );
		}

		/**
		 * Register styles and scripts
		 */
		public function register_styles_and_scripts() {
			global $wp_scripts, $woocommerce, $wp_version;

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Register scripts.
			wp_register_script( 'aj-colorpicker', DIDO_CORE_PLUGIN_URL . '/assets/js/aj-colorpicker.min.js', array( 'jquery', 'wp-color-picker' ), '3.0.0', true );
			wp_register_script( 'aj-plugin-fw-fields', DIDO_CORE_PLUGIN_URL . '/assets/js/aj-fields' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', 'aj-colorpicker', 'jquery-ui-slider', 'jquery-ui-sortable' ), $this->version, true );
			wp_register_script( 'aj-date-format', DIDO_CORE_PLUGIN_URL . '/assets/js/aj-date-format' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, true );

			wp_register_script( 'dido-metabox', DIDO_CORE_PLUGIN_URL . '/assets/js/metabox' . $suffix . '.js', array( 'jquery', 'wp-color-picker', 'aj-plugin-fw-fields' ), $this->version, true );
			wp_register_script( 'dido-plugin-panel', DIDO_CORE_PLUGIN_URL . '/assets/js/dido-plugin-panel' . $suffix . '.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'aj-plugin-fw-fields' ), $this->version, true );
			wp_register_script( 'colorbox', DIDO_CORE_PLUGIN_URL . '/assets/js/jquery.colorbox' . $suffix . '.js', array( 'jquery' ), '1.6.3', true );
			wp_register_script( 'aj_how_to', DIDO_CORE_PLUGIN_URL . '/assets/js/how-to' . $suffix . '.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'aj-plugin-fw-wp-pages', DIDO_CORE_PLUGIN_URL . '/assets/js/wp-pages' . $suffix . '.js', array( 'jquery' ), $this->version, false );

			// Register styles.
			wp_register_style( 'dido-plugin-style', DIDO_CORE_PLUGIN_URL . '/assets/css/dido-plugin-panel.css', array(), $this->version );
			wp_register_style( 'jquery-ui-style', DIDO_CORE_PLUGIN_URL . '/assets/css/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );
			wp_register_style( 'colorbox', DIDO_CORE_PLUGIN_URL . '/assets/css/colorbox.css', array(), $this->version );
			wp_register_style( 'dido-upgrade-to-pro', DIDO_CORE_PLUGIN_URL . '/assets/css/dido-upgrade-to-pro.css', array( 'colorbox' ), $this->version );
			wp_register_style( 'dido-plugin-metaboxes', DIDO_CORE_PLUGIN_URL . '/assets/css/metaboxes.css', array(), $this->version );
			wp_register_style( 'aj-plugin-fw-fields', DIDO_CORE_PLUGIN_URL . '/assets/css/aj-fields.css', false, $this->version );

			wp_register_style( 'raleway-font', '//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900', array(), $this->version );

			$wc_version_suffix = '';
			if ( function_exists( 'WC' ) || ! empty( $woocommerce ) ) {
				$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
				$wc_version_suffix   = version_compare( $woocommerce_version, '3.0.0', '>=' ) ? '' : '-wc-2.6';

				wp_register_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), $woocommerce_version );
			} else {
				wp_register_script( 'select2', DIDO_CORE_PLUGIN_URL . '/assets/js/select2/select2.min.js', array( 'jquery' ), '4.0.3', true );
				wp_register_style( 'aj-select2-no-wc', DIDO_CORE_PLUGIN_URL . '/assets/css/aj-select2-no-wc.css', false, $this->version );
			}

			wp_register_script( 'aj-enhanced-select', DIDO_CORE_PLUGIN_URL . '/assets/js/aj-enhanced-select' . $wc_version_suffix . $suffix . '.js', array( 'jquery', 'select2' ), $this->version, true );
			wp_localize_script(
				'aj-enhanced-select',
				'aj_framework_enhanced_select_params',
				array(
					'ajax_url'               => admin_url( 'admin-ajax.php' ),
					'search_posts_nonce'     => wp_create_nonce( 'search-posts' ),
					'search_terms_nonce'     => wp_create_nonce( 'search-terms' ),
					'search_customers_nonce' => wp_create_nonce( 'search-customers' ),
				)
			);

			wp_localize_script(
				'aj-plugin-fw-fields',
				'aj_framework_fw_fields',
				array(
					'admin_url' => admin_url( 'admin.php' ),
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
				)
			);

			// Localize color-picker to avoid issues with WordPress 5.5.
			if ( version_compare( $wp_version, '5.5-RC', '>=' ) ) {
				wp_localize_script(
					'aj-colorpicker',
					'wpColorPickerL10n',
					array(
						'clear'            => __( 'Clear' ),
						'clearAriaLabel'   => __( 'Clear color' ),
						'defaultString'    => __( 'Default' ),
						'defaultAriaLabel' => __( 'Select default color' ),
						'pick'             => __( 'Select Color' ),
						'defaultLabel'     => __( 'Color value' ),
					)
				);
			}

			wp_enqueue_style( 'aj-plugin-fw-admin', DIDO_CORE_PLUGIN_URL . '/assets/css/admin.css', array(), $this->version );
		}
	}
}

DIDO_Assets::instance();
