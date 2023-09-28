<?php
/**
 * Init class
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'AJ_WCWL_Frontend_Premium' ) ) {
	/**
	 * Frontend class
	 *
	 * @since 1.0.0
	 */
	class AJ_WCWL_Frontend_Premium extends AJ_WCWL_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \AJ_WCWL_Frontend_Premium
		 * @since 2.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \AJ_WCWL_Frontend_Premium
		 * @since 2.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct();

			// init widget
			add_action( 'widgets_init', array( $this, 'register_widget' ) );

			// register scripts for premium features
			add_filter( 'aj_wcwl_main_script_deps', array( $this, 'filter_dependencies' ) );

			// prints wishlist pages links
			add_action( 'aj_wcwl_wishlist_before_wishlist_content', array( $this, 'add_back_to_all_wishlists_link' ), 20, 1 );
			add_action( 'aj_wcwl_wishlist_after_wishlist_content', array( $this, 'add_wishlist_links' ) );
			add_action( 'aj_wcwl_wishlist_after_wishlist_content', array( $this, 'add_new_wishlist_popup' ), 20 );

			// redirection for unauthenticated users
			add_action( 'template_redirect', array( $this, 'redirect_unauthenticated_users' ) );
			add_action( 'template_redirect', array( $this, 'add_wishlist_login_notice' ) );
			add_action( 'init', array( $this, 'add_wishlist_notice' ) );
			add_filter( 'woocommerce_login_redirect', array( $this, 'login_register_redirect' ) );
			add_filter( 'woocommerce_registration_redirect', array( $this, 'login_register_redirect' ) );

			// error when visiting private wishlists
			add_action( 'template_redirect', array( $this, 'private_wishlist_404' ) );
		}

		/**
		 * Filter dependencies for the main script, allowing to hook additional scripts required by premium features
		 *
		 * @param $deps array Original dependencies
		 * @return array Filtered dependencies
		 */
		public function filter_dependencies( $deps ) {
			if( 'yes' == get_option( 'aj_wcwl_enable_drag_and_drop', 'no' ) ){
				$deps[] = 'jquery-ui-sortable';
			}

			return $deps;
		}

		/**
		 * Return localize array
		 *
		 * @return array Array with variables to be localized inside js
		 * @since 2.2.3
		 */
		public function get_localize() {
			$localize = parent::get_localize();

			$localize['multi_wishlist'] = defined( 'AJ_WCWL_PREMIUM' ) && AJ_WCWL()->is_multi_wishlist_enabled() && 'default' != get_option( 'aj_wcwl_modal_enable', 'yes' );
			$localize['modal_enable'] = 'yes' == get_option( 'aj_wcwl_modal_enable', 'yes' );
			$localize['enable_drag_n_drop'] = 'yes' == get_option( 'aj_wcwl_enable_drag_and_drop', 'no' );
			$localize['enable_tooltip'] = 'yes' == get_option( 'aj_wcwl_tooltip_enable', 'no' );
			$localize['enable_notices'] = 'yes' == get_option( 'aj_wcwl_notices_enable', 'yes' );
			$localize['auto_close_popup'] = 'close' == get_option( 'aj_wcwl_modal_close_behaviour', 'close' );
			$localize['popup_timeout'] = apply_filters( 'aj_wcwl_popup_timeout', 3000 );

			$localize['actions']['move_to_another_wishlist_action'] = 'move_to_another_wishlist';
			$localize['actions']['delete_item_action'] = 'delete_item';
			$localize['actions']['sort_wishlist_items'] = 'sort_wishlist_items';
			$localize['actions']['update_item_quantity'] = 'update_item_quantity';
			$localize['actions']['ask_an_estimate'] = 'ask_an_estimate';
			$localize['actions']['remove_from_all_wishlists'] = 'remove_from_all_wishlists';

			return $localize;
		}

		/**
		 * Generate CSS code to append to each page, to apply custom style to wishlist elements
		 *
		 * @param $rules array Array of additional rules to add to default ones
		 * @return string Generated CSS code
		 */
		protected function _build_custom_css( $rules = array() ){
			$rules = array_merge(
				array(
					'color_ask_an_estimate' => array(
						'selector' => '.woocommerce a.button.ask-an-estimate-button',
						'rules'    => array(
							'background' => array(
								'rule'    => 'background-color: %s',
								'default' => '#333333'
							),
							'text' => array(
								'rule'    => 'color: %s',
								'default' => '#ffffff'
							),
							'border' => array(
								'rule'    => 'border-color: %s',
								'default' => '#333333'
							),
							'background_hover' => array(
								'rule'    => 'background-color: %s',
								'default' => '#4F4F4F',
								'status' => ':hover'
							),
							'text_hover' => array(
								'rule'    => 'color: %s',
								'default' => '#ffffff',
								'status' => ':hover'
							),
							'border_hover' => array(
								'rule'    => 'border-color: %s',
								'default' => '#4F4F4F',
								'status' => ':hover'
							)
						),
						'deps' => array(
							'aj_wcwl_ask_an_estimate_style' => 'button_custom'
						)
					),
					'ask_an_estimate_rounded_corners_radius' => array(
						'selector' => '.woocommerce a.button.ask-an-estimate-button',
						'rules' => array(
							'rule' => 'border-radius: %dpx',
							'default' => 16
						),
						'deps' => array(
							'aj_wcwl_ask_an_estimate_style' => 'button_custom',
						)
					),
					'tooltip_color' => array(
						'selector' => '.aj-wcwl-tooltip, .with-tooltip .aj-wcwl-tooltip:before, .with-dropdown .with-tooltip .aj-wcwl-tooltip:before',
						'rules' => array(
							'background' => array(
								'rule'    => 'background-color: %1$s; border-bottom-color: %1$s; border-top-color: %1$s',
								'default' => '#333333'
							),
							'text' => array(
								'rule'    => 'color: %s',
								'default' => '#ffffff'
							),
						),
						'deps' => array(
							'aj_wcwl_tooltip_enable' => 'yes'
						)
					)
				),
				$rules
			);

			return parent::_build_custom_css( $rules );
		}

		/* === WIDGETS === */

		/**
		 * Registers widget used to show wishlist list
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_widget() {
			register_widget( 'AJ_WCWL_Widget' );
			register_widget( 'AJ_WCWL_Items_Widget' );
		}

		/* === TEMPLATE MODIFICATIONS === */

		/**
		 * Prints link to get back to manage wishlists view, when you're on wishlist page and multiwishlist is enabled
		 *
		 * @return void
		 * @since 3.0.0
		 */
		public function add_back_to_all_wishlists_link( $var ) {
			$multi_wishlist = AJ_WCWL()->is_multi_wishlist_enabled();

			if( $multi_wishlist && isset( $var['template_part'] ) && 'view' == $var['template_part'] && apply_filters( 'aj_wcwl_show_back_to_all_wishlists_link', true ) ){
				$back_to_all_wishlists_link = sprintf( '<a href="%s" title="%s">%s</a>', AJ_WCWL()->get_wishlist_url( 'manage' ), __( 'Back to all wishlists', 'aj-woocommerce-wishlist' ), apply_filters( 'aj_wcwl_back_to_all_wishlists_link_text', __( '&lsaquo; Back to all wishlists', 'aj-woocommerce-wishlist' ) ) );
				echo '<div class="back-to-all-wishlists">' . $back_to_all_wishlists_link . '</div>';
			}
		}

		/**
		 * Print Create new wishlist popup when needed
		 *
		 * @return void
		 * @since 3.0.0
		 */
		public function add_new_wishlist_popup() {
			$create_in_popup = get_option( 'aj_wcwl_create_wishlist_popup' );
			$add_wishlist_link = get_option( 'aj_wcwl_enable_wishlist_links' );

			$icon = get_option( 'aj_wcwl_add_to_wishlist_icon' );
			$custom_icon = get_option( 'aj_wcwl_add_to_wishlist_custom_icon' );

			if( 'custom' == $icon ){
				$heading_icon = '<img src="' . $custom_icon . '" width="32" />';
			}
			else{
				$heading_icon = ! empty( $icon ) ? '<i class="fa ' . $icon . '"></i>' : '';
			}

			if( 'yes' != $create_in_popup ){
				return;
			}

			if( 'yes' != $add_wishlist_link && ! AJ_WCWL()->is_endpoint( 'manage' ) ){
				return;
			}

			aj_wcwl_get_template_part( 'popup', 'create', '', array(
				'heading_icon' => $heading_icon
			) );
		}

		/**
		 * Add wishlist anchors after wishlist table
		 *
		 * @return void
		 * @since 2.0.5
		 */
		public function add_wishlist_links( $args = [] ) {
			$defaults = array(
				// general
				'add_wishlist_link' => get_option( 'aj_wcwl_enable_wishlist_links' ),
				'create_in_popup' => get_option( 'aj_wcwl_create_wishlist_popup' ),
				'multi_wishlist_enabled' => AJ_WCWL()->is_multi_wishlist_enabled(),
				'order' => array( 'create', 'manage', 'view', 'search' ),

				// create
				'create_url' => AJ_WCWL()->get_wishlist_url( 'create' ),
				'create_label' => apply_filters( 'aj_wcwl_create_wishlist_title_label', __( 'Create a wishlist', 'aj-woocommerce-wishlist' ) ),
				'create_title' => apply_filters( 'aj_wcwl_create_wishlist_title', __( 'Create a wishlist', 'aj-woocommerce-wishlist' ) ),
				'create_class' => AJ_WCWL()->is_endpoint( 'create' ) ? 'active' : '',

				// search
				'search_url' => AJ_WCWL()->get_wishlist_url( 'search' ),
				'search_label' => apply_filters( 'aj_wcwl_search_wishlist_title_label', __( 'Search wishlist', 'aj-woocommerce-wishlist' ) ),
				'search_title' => apply_filters( 'aj_wcwl_search_wishlist_title', __( 'Search wishlist', 'aj-woocommerce-wishlist' ) ),
				'search_class' => AJ_WCWL()->is_endpoint( 'search' ) ? 'active' : '',

				// manage
				'manage_url' => AJ_WCWL()->get_wishlist_url( 'manage' ),
				'manage_label' => apply_filters( 'aj_wcwl_manage_wishlist_title_label', __( 'Your wishlists', 'aj-woocommerce-wishlist' ) ),
				'manage_title' => apply_filters( 'aj_wcwl_manage_wishlist_title', __( 'Manage wishlists', 'aj-woocommerce-wishlist' ) ),
				'manage_class' => AJ_WCWL()->is_endpoint( 'manage' ) ? 'active' : '',

				// view
				'view_url' => AJ_WCWL()->get_wishlist_url(),
				'view_label' => apply_filters( 'aj_wcwl_view_wishlist_title_label', __( 'Your wishlist', 'aj-woocommerce-wishlist' ) ),
				'view_title' => apply_filters( 'aj_wcwl_view_wishlist_title', __( 'View your wishlists', 'aj-woocommerce-wishlist' ) ),
				'view_class' => AJ_WCWL()->is_endpoint( 'view' ) ? 'active' : '',
			);
			$args = wp_parse_args( $args, $defaults );

			/**
			 * @var $add_wishlist_link
			 * @var $create_in_popup
			 * @var $multi_wishlist_enabled
			 * @var $order
			 * @var $create_url
			 * @var $create_label
			 * @var $create_title
			 * @var $create_class
			 * @var $search_url
			 * @var $search_label
			 * @var $search_title
			 * @var $search_class
			 * @var $manage_url
			 * @var $manage_label
			 * @var $manage_title
			 * @var $manage_class
			 * @var $view_url
			 * @var $view_label
			 * @var $view_title
			 * @var $view_class
			 */
			extract( $args );

			if ( 'yes' === $add_wishlist_link ) {
				$create_custom_attributes = '';

				if ( 'yes' == $create_in_popup ) {
					$create_url = '#create_new_wishlist';
					$create_custom_attributes = 'data-rel="prettyPhoto[create_wishlist]"';
				}

				$action_links = array();
				$anchors = array(
					'manage' => sprintf( '<a href="%s" class="manage %s" title="%s">%s</a>', $manage_url, $manage_class, $manage_title, $manage_label ),
					'create' => sprintf( '<a href="%s" class="create %s" title="%s" %s>%s</a>', $create_url, $create_class, $create_title, $create_custom_attributes, $create_label ),
					'search' => sprintf( '<a href="%s" class="search %s" title="%s">%s</a>', $search_url, $search_class, $search_title, $search_label ),
					'view'   => sprintf( '<a href="%s" class="view %s" title="%s">%s</a>', $view_url, $view_class, $view_title, $view_label ),
				);

				foreach ( $order as $endpoint ) {
					if ( ! isset( $anchors[ $endpoint ] ) ) {
						continue;
					}

					if ( ! $multi_wishlist_enabled && in_array( $endpoint, array( 'create', 'manage' ) ) ) {
						continue;
					}

					if ( $multi_wishlist_enabled && in_array( $endpoint, array( 'view' ) ) ) {
						continue;
					}

					$action_links[] = $anchors[ $endpoint ];
				}

				$action_links = apply_filters( 'aj_wcwl_action_links', $action_links );

				echo '<div class="wishlist-page-links">' . implode( ' | ', $action_links ) . '</div>';
			}
		}

		/**
		 * Returns message to show on Manage view, when no wishlist is defined
		 *
		 * @return string HTML for No Wishlist Message.
		 */
		public function get_no_wishlist_message() {
			$create_url               = AJ_WCWL()->get_wishlist_url( 'create' );
			$create_in_popup          = get_option( 'aj_wcwl_create_wishlist_popup' );
			$create_title             = apply_filters( 'aj_wcwl_create_wishlist_title', __( 'Create a wishlist', 'aj-woocommerce-wishlist' ) );
			$create_custom_attributes = '';

			if ( 'yes' === $create_in_popup ) {
				$create_custom_attributes = 'data-rel="prettyPhoto[create_wishlist]"';
				$create_url               = '#create_new_wishlist';
			}

			// translators: 1. Create new wishlist url. 2. Create new wishlist title. 3. Custom attributes for create new wishlist anchor.
			$message = sprintf( __( 'You don\'t have any wishlist yet. <a href="%1$s" title="%2$s" %3$s>Create your first wishlist &rsaquo;</a>', 'aj-woocommerce-wishlist' ), $create_url, $create_title, $create_custom_attributes );

			return apply_filters( 'aj_wcwl_no_wishlist_message', $message );
		}

		/**
		 * Add login notice
		 *
		 * @return void
		 * @since 2.0.5
		 */
		public function add_wishlist_login_notice(){
			$login_notice = get_option( 'aj_wcwl_show_login_notice' );
			$login_text = get_option( 'aj_wcwl_login_anchor_text' );
			$enable_multi_wishlist = get_option( 'aj_wcwl_multi_wishlist_enable' );
			$enable_multi_wishlist_for_unauthenticated_users = get_option( 'aj_wcwl_enable_multi_wishlist_for_unauthenticated_users' );
			$wishlist_page_id = AJ_WCWL()->get_wishlist_page_id();

			if(
				empty( $login_notice ) ||
				( strpos( $login_notice, '%login_anchor%' ) !== false && empty( $login_text ) ) ||
				! is_page( $wishlist_page_id ) ||
				is_user_logged_in() ||
				'no' == $enable_multi_wishlist ||
				'yes' == $enable_multi_wishlist_for_unauthenticated_users
			){
				return;
			}

			$redirect_url = apply_filters( 'aj_wcwl_redirect_url', wc_get_page_permalink( 'myaccount' ) );
			$redirect_url = add_query_arg( 'wishlist-redirect', urlencode( add_query_arg( array() ) ), $redirect_url );

			$login_notice = str_replace( '%login_anchor%', sprintf( '<a href="%s">%s</a>', $redirect_url, apply_filters( 'aj_wcwl_login_in_text', $login_text ) ), $login_notice );
			wc_add_notice( apply_filters('aj_wcwl_login_notice',$login_notice), 'notice' );
		}

		/**
		 * Redirect unauthenticated users to login page
		 *
		 * @return void
		 * @since 2.0.5
		 */
		public function redirect_unauthenticated_users() {
			$disable_wishlist = get_option( 'aj_wcwl_disable_wishlist_for_unauthenticated_users' );
			$wishlist_page_id = AJ_WCWL()->get_wishlist_page_id();

			$user_agent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : false;
			$is_facebook_scraper = in_array( $user_agent, array(
				'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
				'facebookexternalhit/1.1',
				'Facebot'
			) );

			$action_params = get_query_var( AJ_WCWL()->wishlist_param, false );
			$action_params = explode( '/', apply_filters( 'aj_wcwl_current_wishlist_view_params', $action_params ) );

			$is_share_url = in_array( $action_params[0], array( 'view', 'user' ) ) && ! empty( $action_params[1] );

			if( $disable_wishlist == 'yes' && ! is_user_logged_in() && is_page( $wishlist_page_id ) && $wishlist_page_id != wc_get_page_id( 'myaccount' ) && ! $is_facebook_scraper && ! $is_share_url ){
				wp_redirect( esc_url_raw( add_query_arg( 'wishlist_notice', 'true', wc_get_page_permalink( 'myaccount' ) ) ) );
				die();
			}
		}

		/**
		 * Add login notice after wishlist redirect
		 *
		 * @return void
		 * @since 2.0.5
		 */
		public function add_wishlist_notice() {
			$disable_wishlist = get_option( 'aj_wcwl_disable_wishlist_for_unauthenticated_users' );
			if( apply_filters( 'aj_wcwl_add_wishlist_notice', $disable_wishlist == 'yes' ) && isset( $_GET['wishlist_notice'] ) && $_GET['wishlist_notice'] == true && ! isset( $_POST['login'] ) && ! isset( $_POST['register'] ) ){
				wc_add_notice( apply_filters( 'aj_wcwl_wishlist_disabled_for_unauthenticated_user_message', __( 'Please, log in to use the wishlist features', 'aj-woocommerce-wishlist' ) ), 'error' );
			}
		}

		/**
		 * Add login redirect for wishlist
		 *
		 * @param $redirect string Url where to redirect after login
		 *
		 * @return string
		 * @since 2.0.6
		 */
		public function login_register_redirect( $redirect ) {
			if( isset( $_GET['wishlist_notice'] ) && $_GET['wishlist_notice'] == true ){
				$redirect = AJ_WCWL()->get_wishlist_url();

				if( isset( $_GET['add_to_wishlist'] ) ){
					$redirect = add_query_arg( 'add_to_wishlist', $_GET['add_to_wishlist'], $redirect );
				}
			}
			elseif( isset( $_GET['wishlist-redirect'] ) ){
				$redirect = esc_url_raw( urldecode( $_GET['wishlist-redirect'] ) );
			}

			return apply_filters('aj_wcwl_login_register_redirect',$redirect);
		}

		/**
		 * Generates image tag for the product, where src attribute is populated with an absolute path, instead of an url
		 * This is required for dompdf library to create a pdf containing images
		 *
		 * @param $product \WC_Product Product object
		 * @return string Image tag
		 * @since 3.0.0
		 */
		public function get_product_image_with_path( $product ) {
			$image_id = $product->get_image_id();

			if ( $image_id ) {
				$thumbnail_id  = $image_id;
				$thumbnail_url = apply_filters( 'aj_wcwl_product_thumbnail', get_attached_file( $thumbnail_id ), $thumbnail_id );
			}

			if( empty( $thumbnail_url ) ) {
				$thumbnail_url = function_exists( 'wc_placeholder_img_src' ) ? str_replace( get_home_url(), ABSPATH, wc_placeholder_img_src() ) : '';
			}

			return apply_filters( 'aj_wcwl_get_product_image_with_path', sprintf( '<img src="%s" style="max-width:100px;"/>', $thumbnail_url ), $thumbnail_url );
		}

		/**
		 * Set 404 status when non-owner user tries to visit private wishlist
		 *
		 * @return void
		 * @since 3.0.7
		 */
		public function private_wishlist_404() {
			global $wp_query;

			if( ! aj_wcwl_is_wishlist_page() ){
				return;
			}

			$current_wishlist = AJ_WCWL_Wishlist_Factory::get_current_wishlist();

			if( ! $current_wishlist || $current_wishlist->current_user_can( 'view' ) ){
				return;
			}

			// if we're trying to show private wishlist to non-owner user, return 404
			$wp_query->set_404();
			status_header(404);
		}
	}
}

/**
 * Unique access to instance of AJ_WCWL_Frontend class
 *
 * @return \AJ_WCWL_Frontend_Premium
 * @since 2.0.0
 */
function AJ_WCWL_Frontend_Premium(){
	return AJ_WCWL_Frontend_Premium::get_instance();
}
