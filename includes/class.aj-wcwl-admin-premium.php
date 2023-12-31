<?php
/**
 * Init premium admin features of the plugin
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'AJ_WCWL_Admin_Premium' ) ) {
	/**
	 * WooCommerce Wishlist admin Premium
	 *
	 * @since 1.0.0
	 */
	class AJ_WCWL_Admin_Premium extends AJ_WCWL_Admin {

		/**
		 * Single instance of the class
		 *
		 * @var \AJ_WCWL_Admin
		 * @since 2.0.0
		 */
		protected static $instance;

		/**
		 * Various links
		 *
		 * @var string
		 * @access public
		 * @since 1.0.0
		 */
		public $showcase_images = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \AJ_WCWL_Admin_Premium
		 * @since 2.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor of the class
		 *
		 * @return AJ_WCWL_Admin_Premium
		 * @since 2.0.0
		 */
		public function __construct() {
			parent::__construct();

			// register admin notices.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// add premium settings.
			add_filter( 'aj_wcwl_settings_options', array( $this, 'add_settings_options' ) );
			add_filter( 'aj_wcwl_add_to_wishlist_options', array( $this, 'add_add_to_wishlist_options' ) );
			add_filter( 'aj_wcwl_wishlist_page_options', array( $this, 'add_wishlist_options' ) );

			// register custom panel handling.
			add_action( 'aj_wcwl_after_popular_table', array( $this, 'print_promotion_wizard' ) );

			// register admin actions.
			add_action( 'admin_action_export_users', array( $this, 'export_users_via_csv' ) );
			add_action( 'admin_action_delete_wishlist', array( $this, 'delete_wishlist_from_actions' ) );
			add_action( 'admin_action_send_promotion', array( $this, 'trigger_promotion_email' ) );
			add_action( 'admin_action_delete_promotion_draft', array( $this, 'delete_promotion_draft' ) );

			// adds column to product page.
			add_filter( 'manage_edit-product_columns', array( $this, 'add_product_columns' ) );
			add_filter( 'manage_edit-product_sortable_columns', array( $this, 'product_sortable_columns' ) );
			add_action( 'manage_product_posts_custom_column', array( $this, 'render_product_columns' ) );
			add_filter( 'request', array( $this, 'product_request_query' ) );

			// send promotion email.
			add_action( 'wp_ajax_preview_promotion_email', array( $this, 'ajax_preview_promotion_email' ) );
			add_action( 'wp_ajax_calculate_promotion_email_receivers', array( $this, 'ajax_calculate_promotion_email_receivers' ) );

			// compatibility with email templates.
			add_filter( 'aj_wcet_email_template_types', array( $this, 'register_emails_for_custom_templates' ) );

			// admin only ajax.
			add_action( 'wp_ajax_json_search_coupons', array( $this, 'json_search_coupons' ) );
		}

		/* === INITIALIZATION SECTION === */

		/**
		 * Initiator method. Initiate properties.
		 *
		 * @return void
		 * @access private
		 * @since 1.0.0
		 */
		public function init() {
			parent::init();

			// remove premium tab, if any.
			if ( isset( $this->available_tabs['premium'] ) ) {
				unset( $this->available_tabs['premium'] );
			}

			// add new tabs.
			$this->available_tabs = apply_filters(
				'aj_wcwl_available_admin_tabs_premium',
				array_merge(
					$this->available_tabs,
					array(
						'lists' => __( 'All wishlists', 'aj-woocommerce-wishlist' ),
						'popular' => __( 'Popular', 'aj-woocommerce-wishlist' ),
						'ask_an_estimate' => __( 'Ask for an estimate', 'aj-woocommerce-wishlist' ),
						'promotion_email' => __( 'Promotional', 'aj-woocommerce-wishlist' ),
					)
				)
			);
		}

		/**
		 * Add new options to general settings tab
		 *
		 * @param array $options Array of available options.
		 * @return array Filtered array of options
		 */
		public function add_settings_options( $options ) {
			$settings = $options['settings'];

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'disable_wishlist_for_unauthenticated_users' => array(
						'name'      => __( 'Enable wishlist for', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Choose whether to enable the wishlist feature for all users or only for logged-in users', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_disable_wishlist_for_unauthenticated_users',
						'options'   => array(
							'no'  => __( 'All users', 'aj-woocommerce-wishlist' ),
							'yes' => __( 'Only authenticated users', 'aj-woocommerce-wishlist' ),
						),
						'default'   => 'no',
						'type'      => 'aj-field',
						'aj-type' => 'radio',
					),

					'enable_add_to_wishlist_notices' => array(
						'name'      => __( 'Enable Added/Removed notices', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Enable popup notices when the product is added or removed from the wishlist', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_notices_enable',
						'default'   => 'yes',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
					),

					'enable_add_to_wishlist_tooltip' => array(
						'name'      => __( 'Enable "Add to wishlist" tooltip', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Choose whether to display a tooltip when hovering over Add to wishlist link', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_tooltip_enable',
						'default'   => 'no',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
					),

					'add_to_wishlist_tooltip_style' => array(
						'name'         => __( 'Add to wishlist tooltip style', 'aj-woocommerce-wishlist' ),
						'desc'         => __( 'Choose colors for Add to wishlist tooltip', 'aj-woocommerce-wishlist' ),
						'id'           => 'aj_wcwl_tooltip_color',
						'type'         => 'aj-field',
						'aj-type'    => 'multi-colorpicker',
						'colorpickers' => array(
							array(
								'name' => __( 'Background', 'aj-woocommerce-wishlist' ),
								'id'   => 'background',
								'default' => '#333',
							),
							array(
								'name' => __( 'Text', 'aj-woocommerce-wishlist' ),
								'id'   => 'text',
								'default' => '#fff',
							),
						),
						'deps' => array(
							'id' => 'aj_wcwl_tooltip_enable',
							'value' => 'yes',
						),
					),
				),
				'general_section_start'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'multi_wishlist_section_start' => array(
						'name' => __( 'Multi-wishlist settings', 'aj-woocommerce-wishlist' ),
						'type' => 'title',
						'desc' => '',
						'id' => 'aj_wcwl_multi_wishlist_settings',
					),

					'enable_multi_wishlist' => array(
						'name'      => __( 'Enable multi-wishlist feature', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Allow customers to create and manage multiple wishlists', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_multi_wishlist_enable',
						'default'   => 'no',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
					),

					'enable_multi_wishlist_for_unauthenticated_users' => array(
						'name'      => __( 'Enable multiple wishlists for', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Choose whether to enable the multi-wishlist feature for all users or just for logged-in users', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_enable_multi_wishlist_for_unauthenticated_users',
						'options'   => array(
							'yes' => __( 'All users', 'aj-woocommerce-wishlist' ),
							'no'  => __( 'Only authenticated users', 'aj-woocommerce-wishlist' ),
						),
						'default'   => 'no',
						'type'      => 'aj-field',
						'aj-type' => 'radio',
					),

					'show_login_notice' => array(
						'name'      => __( 'Login message for non-authenticated users', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_show_login_notice',
						'desc'      => __( 'Enter the message to ask unauthenticated users to login so they will be able to use the multi-wishlist feature.<br/>Use the placeholder %login_anchor% (set up the text in the following option) to add an anchor and redirect users to the Login page.', 'aj-woocommerce-wishlist' ),
						'default'   => __( 'Please %login_anchor% to use all the wishlist features', 'aj-woocommerce-wishlist' ),
						'type'      => 'aj-field',
						'aj-type' => 'text',
					),

					'login_anchor_text' => array(
						'name'      => __( 'Login anchor text', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_login_anchor_text',
						'desc'      => __( 'Set up here the text of the Login link that replace %login_anchor%', 'aj-woocommerce-wishlist' ),
						'default'   => __( 'login', 'aj-woocommerce-wishlist' ),
						'type'      => 'aj-field',
						'aj-type' => 'text',
					),

					'multi_wishlist_section_end' => array(
						'type' => 'sectionend',
						'id' => 'aj_wcwl_multi_wishlist_settings',
					),
				),
				'general_section_end'
			);

			$options['settings'] = $settings;

			return $options;
		}

		/**
		 * Add new options to Add to Wishlist settings tab
		 *
		 * @param array $options Array of available options.
		 * @return array Filtered array of options
		 */
		public function add_add_to_wishlist_options( $options ) {
			$settings = $options['add_to_wishlist'];

			$multi_wishlist_enabled = 'yes' == get_option( 'aj_wcwl_multi_wishlist_enable', 'yes' );

			if ( $multi_wishlist_enabled ) {
				$settings = aj_wcwl_merge_in_array(
					$settings,
					array(
						'enable_add_to_wishlist_modal' => array(
							'name'      => __( 'When clicking on Add to wishlist', 'aj-woocommerce-wishlist' ),
							'desc'      => __( 'Choose the default action for new products added to the wishlist.', 'aj-woocommerce-wishlist' ),
							'id'        => 'aj_wcwl_modal_enable',
							'default'   => 'yes',
							'type'      => 'aj-field',
							'aj-type' => 'radio',
							'options'   => array(
								'default' => __( 'Automatically add to the default list', 'aj-woocommerce-wishlist' ),
								'yes'     => __( 'Show a modal window to allow users to choose a wishlist', 'aj-woocommerce-wishlist' ),
								'no'      => __( 'Show a dropdown to allow users to choose a wishlist', 'aj-woocommerce-wishlist' ),
							),
						),
						'add_to_wishlist_modal_closing_behaviour' => array(
							'name'      => __( 'When product is added to wishlist', 'aj-woocommerce-wishlist' ),
							'desc'      => __( 'Choose what should happen to the modal, when a product is added to the list.', 'aj-woocommerce-wishlist' ),
							'id'        => 'aj_wcwl_modal_close_behaviour',
							'default'   => 'close',
							'type'      => 'aj-field',
							'aj-type' => 'radio',
							'deps'      => array(
								'id'    => 'aj_wcwl_modal_enable',
								'value' => 'yes',
							),
							'options'   => array(
								'close'   => __( 'Automatically close the modal', 'aj-woocommerce-wishlist' ),
								'open'    => __( 'Leave the modal open', 'aj-woocommerce-wishlist' ),
							),
						),
					),
					'general_section_start'
				);

				$settings['after_add_to_wishlist_behaviour']['options']['modal'] = __( 'Add to wishlist button now opens a modal to move or remove items (available only with multi-wishlist option enabled)', 'aj-woocommerce-wishlist' );
			}

			// add options for product page.
			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'show_times_in_wishlist' => array(
						'name'      => __( 'Show a count of users with a specific product in wishlist', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Show a counter on the product page that allows your customers to know how may times the product has been added to a wishlist', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_show_counter',
						'default'   => 'no',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
					),
				),
				'add_to_wishlist_position'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'add_to_wishlist_popup_text' => array(
						'name'    => __( '"Add to wishlist" popup button text', 'aj-woocommerce-wishlist' ),
						'id'      => 'aj_wcwl_add_to_wishlist_popup_text',
						'desc'    => __( 'Text of the "Add to wishlist" button in the popup', 'aj-woocommerce-wishlist' ),
						'default' => __( 'Add to wishlist', 'aj-woocommerce-wishlist' ),
						'type'    => 'text',
					),
				),
				'already_in_wishlist_text'
			);

			$options['add_to_wishlist'] = $settings;

			return $options;
		}

		/**
		 * Add new options to wishlist settings tab
		 *
		 * @param array $options Array of available options.
		 * @return array Filtered array of options
		 */
		public function add_wishlist_options( $options ) {
			$settings = $options['wishlist_page'];

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'wishlist_manage_layout' => array(
						'name'     => __( 'Layout for wishlist view', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Select a style for your "Manage wishlists" page', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_wishlist_manage_layout',
						'type'     => 'aj-field',
						'aj-type' => 'radio',
						'options'   => array(
							'traditional' => __( 'Traditional', 'aj-woocommerce-wishlist' ),
							'modern'      => __( 'Modern grid', 'aj-woocommerce-wishlist' ),
						),
						'default'  => 'traditional',
					),
					'show_manage_num_of_items' => array(
						'name'     => __( 'Show wishlist info', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Number of items in wishlist', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_manage_num_of_items_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'start',
					),
					'show_manage_creation_date' => array(
						'name'     => __( 'Show wishlist info', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Date of creation of the wishlist', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_manage_creation_date_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'manage_info',
					),
					'show_manage_download_pdf' => array(
						'name'     => __( 'Show wishlist info', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Download a PDF version of the wishlist', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_manage_download_pdf_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'manage_info',
					),
					'show_manage_rename_wishlist' => array(
						'name'     => __( 'Show wishlist info', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Rename wishlist button', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_manage_rename_wishlist_show',
						'type'     => 'checkbox',
						'default'  => 'no',
						'checkboxgroup' => 'manage_info',
					),
					'show_manage_delete_wishlist' => array(
						'name'     => __( 'Show wishlist info', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Delete wishlist button', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_manage_delete_wishlist_show',
						'type'     => 'checkbox',
						'default'  => 'yes',
						'checkboxgroup' => 'end',
					),
					'new_wishlist_as_popup' => array(
						'name'      => __( '"Create wishlist" in popup', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Create a new wishlist in the popup instead of using the endpoint', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_create_wishlist_popup',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
						'default'   => 'no',
					),
				),
				'wishlist_page'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'wishlist_layout' => array(
						'name'     => __( 'Layout for product list', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Select a style for displaying your wishlist page', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_wishlist_layout',
						'type'     => 'aj-field',
						'aj-type' => 'radio',
						'default'  => 'traditional',
						'options' => array(
							'traditional' => __( 'Traditional', 'aj-woocommerce-wishlist' ),
							'modern'      => __( 'Modern grid', 'aj-woocommerce-wishlist' ),
							'images'      => __( 'Only images with info at click', 'aj-woocommerce-wishlist' ),
						),
					),
				),
				'wishlist_section_start'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'show_quantity' => array(
						'name'     => __( 'In wishlist table show', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Product quantity (so users can manage the quantity of each product from the wishlist)', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_quantity_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'wishlist_info',
					),
				),
				'show_unit_price'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'show_price_changes' => array(
						'name'     => __( 'In wishlist table show', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Price variation info (show the price difference compared to when the product was added to the list)', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_price_changes_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'wishlist_info',
					),
				),
				'show_unit_price'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'show_cb' => array(
						'name'     => __( 'In wishlist table show', 'aj-woocommerce-wishlist' ),
						'desc'     => __( 'Checkbox to select multiple items, add them to the cart or delete them with one click', 'aj-woocommerce-wishlist' ),
						'id'       => 'aj_wcwl_cb_show',
						'type'     => 'checkbox',
						'default'  => '',
						'checkboxgroup' => 'wishlist_info',
					),
				),
				'show_remove_button'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'show_move_to_another_wishlist' => array(
						'name'      => __( 'Show Move to another wishlist', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Enable the option to move the product to another wishlist', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_show_move_to_another_wishlist',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
						'default'   => '',
					),
					'move_to_another_wishlist_type' => array(
						'name'      => __( 'Move to another wishlist - style', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Choose the look and feel of the "Move to another wishlist" option', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_move_to_another_wishlist_type',
						'type'      => 'aj-field',
						'aj-type' => 'radio',
						'default'   => 'popup',
						'options'   => array(
							'select' => __( 'Select dropdown with all wishlists', 'aj-woocommerce-wishlist' ),
							'popup'  => __( 'Link to a popup', 'aj-woocommerce-wishlist' ),
						),
						'deps'      => array(
							'id'    => 'aj_wcwl_show_move_to_another_wishlist',
							'value' => 'yes',
						),
					),
				),
				'repeat_remove_button'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'enable_add_all_to_cart' => array(
						'name'      => __( 'Enable "Add all to cart"', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Enable "Add all to cart" button to let customers add all the products in the wishlist to the cart', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_enable_add_all_to_cart',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
						'default'   => 'no',
					),
					'enable_drag_n_drop' => array(
						'name'      => __( 'Enable drag and drop option', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Enable drag and drop option so users can arrange the order of products in the wishlist', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_enable_drag_and_drop',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
						'default'   => 'no',
					),
					'enable_wishlist_links' => array(
						'name'      => __( 'Show links to pages', 'aj-woocommerce-wishlist' ),
						'desc'      => __( 'Show the links to "Manage", "Create" and "Search" pages after the wishlist table', 'aj-woocommerce-wishlist' ),
						'id'        => 'aj_wcwl_enable_wishlist_links',
						'type'      => 'aj-field',
						'aj-type' => 'onoff',
						'default'   => 'yes',
					),
				),
				'remove_after_add_to_cart'
			);

			$settings = aj_wcwl_merge_in_array(
				$settings,
				array(
					'create_wishlist_page_title'             => array(
						'name'    => __( '"Create wishlist" page name', 'aj-woocommerce-wishlist' ),
						'id'      => 'aj_wcwl_wishlist_create_title',
						'desc'    => __( 'Enter the title for the "Create wishlist" page', 'aj-woocommerce-wishlist' ),
						'default' => __( 'Create a new wishlist', 'aj-woocommerce-wishlist' ),
						'type'    => 'text',
					),
					'manage_wishlist_page_title' => array(
						'name'    => __( '"Manage wishlist" page name', 'aj-woocommerce-wishlist' ),
						'id'      => 'aj_wcwl_wishlist_manage_title',
						'desc'    => __( 'Enter the title for "Manage wishlists" page', 'aj-woocommerce-wishlist' ),
						'default' => __( 'Your wishlists', 'aj-woocommerce-wishlist' ),
						'type'    => 'text',
					),
					'search_wishlist_page_title' => array(
						'name'    => __( '"Search wishlist" page name', 'aj-woocommerce-wishlist' ),
						'id'      => 'aj_wcwl_wishlist_search_title',
						'desc'    => __( 'Enter the title for "Search wishlists" page', 'aj-woocommerce-wishlist' ),
						'default' => __( 'Search a wishlist', 'aj-woocommerce-wishlist' ),
						'type'    => 'text',
					),
				),
				'default_wishlist_title'
			);

			$options['wishlist_page'] = $settings;

			return $options;
		}

		/* === PANEL HANDLING === */

		/**
		 * Print admin notices for wishlist settings page
		 *
		 * @return void
		 * @since 2.0.7
		 */
		public function admin_notices() {
			if ( isset( $_GET['email_sent'] ) ) {
				$res = is_numeric( $_GET['email_sent'] ) ? intval( $_GET['email_sent'] ) : $_GET['email_sent'];

				if ( $res ) {
					?>
					<div class="updated fade">
						<p><?php esc_html_e( 'Promotional email correctly scheduled', 'aj-woocommerce-wishlist' ); ?></p>
					</div>
					<?php
				} else {
					?>
					<div class="updated fade">
						<p><?php esc_html_e( 'There was an error while scheduling emails; please, try again later', 'aj-woocommerce-wishlist' ); ?></p>
					</div>
					<?php
				}
			}

			if ( isset( $_GET['tab'] ) && 'popular' == $_GET['tab'] ) {
				$promotion_email_draft = get_option( 'aj_wcwl_promotion_draft', array() );

				if ( ! empty( $promotion_email_draft ) ) {
					?>
					<div class="updated fade">
						<p>
							<?php esc_html_e( 'You saved a draft of a promotional email; would you like to continue form there?', 'aj-woocommerce-wishlist' ); ?>
							<a href="#" class="restore-draft button-primary" data-draft="<?php echo htmlspecialchars( json_encode( $promotion_email_draft ) ); ?>"><?php esc_html_e( 'Continue', 'aj-woocommerce-wishlist' ); ?></a>
							<?php esc_html_e( 'or', 'aj-woocommerce-wishlist' ); ?>
							<a href="<?php echo esc_url_raw( add_query_arg( 'action', 'delete_promotion_draft', wp_nonce_url( admin_url( 'admin.php' ) ) ) ); ?>" class="delete-draft button-secondary" onclick="return confirm('<?php esc_html_e( 'Are you sure?', 'aj-woocommerce-wishlist' ); ?>')"><?php esc_html_e( 'Delete draft', 'aj-woocommerce-wishlist' ); ?></a>
						</p>
					</div>
					<?php
				}
			}
		}

		/**
		 * Adds params to use in admin template files
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function print_popular_table() {
			if ( isset( $_GET['action'] ) && 'send_promotional_email' == $_GET['action'] ) {
				$emails = WC_Emails::instance()->get_emails();
				$promotion_email = $emails['AJ_WCWL_Promotion_Email'];

				$additional_info['current_tab'] = 'popular';
				$additional_info['product_id'] = isset( $_REQUEST['product_id'] ) ? intval( $_REQUEST['product_id'] ) : false;
				$additional_info['promotional_email_html_content'] = $promotion_email->get_option( 'content_html' );
				$additional_info['promotional_email_text_content'] = $promotion_email->get_option( 'content_text' );
				$additional_info['coupons'] = get_posts(
					array(
						'post_type' => 'shop_coupon',
						'posts_per_page' => -1,
						'post_status' => 'publish',
					)
				);

				aj_wcwl_get_template( 'admin/wishlist-panel-send-promotional-email.php', $additional_info );
			}
		}

		/**
		 * Print template for Create Promotion wizard
		 *
		 * @return void
		 * @since 3.0.0
		 */
		public function print_promotion_wizard() {
			$emails  = WC()->mailer()->get_emails();
			$email_obj = isset( $emails['AJ_WCWL_Promotion_Email'] ) ? $emails['AJ_WCWL_Promotion_Email'] : false;

			if ( ! $email_obj ) {
				return;
			}

			include AJ_WCWL_DIR . 'templates/admin/promotion-wizard.php';
		}

		/* === REQUEST HANDLING === */

		/**
		 * Handle admin requests to delete a wishlist
		 *
		 * @return void
		 * @since 2.0.6
		 */
		public function delete_wishlist_from_actions() {
			if ( ! empty( $_REQUEST['wishlist_id'] ) ) {
				if ( isset( $_REQUEST['delete_wishlist'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['delete_wishlist'] ) ), 'delete_wishlist' ) ) {
					$wishlist_id = sanitize_text_field( wp_unslash( $_REQUEST['wishlist_id'] ) );
					try {
						AJ_WCWL_Premium()->remove_wishlist( $wishlist_id );
					} catch ( Exception $e ) {
						// do nothing.
					}
				}
			}

			wp_redirect(
				esc_url_raw(
					add_query_arg(
						array(
							'page' => 'aj_wcwl_panel',
							'tab'  => 'list',
						),
						admin_url( 'admin.php' )
					)
				)
			);
			die();
		}

		/**
		 * Export users that added a specific product to their wishlists
		 *
		 * @return void
		 * @since 2.1.3
		 */
		public function export_users_via_csv() {
			$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : false;
			$product = wc_get_product( $product_id );

			$items = AJ_WCWL_Wishlist_Factory::get_wishlist_items(
				array(
					'product_id' => $product_id,
					'user_id' => false,
					'session_id' => false,
					'wishlist_id' => 'all',
				)
			);

			if ( ! empty( $items ) ) {

				$formatted_users = array();

				foreach ( $items as $item ) {
					$user_obj = $item->get_user();
					$user_id = $item->get_user_id();

					if ( ! $user_obj || isset( $formatted_users[ $user_id ] ) ) {
						continue;
					}

					$formatted_users[ $user_id ] = array(
						$user_id,
						$user_obj->user_email,
						! empty( $user_obj->billing_first_name ) ? $user_obj->billing_first_name : $user_obj->first_name,
						! empty( $user_obj->billing_last_name ) ? $user_obj->billing_last_name : $user_obj->last_name,
					);
				}

				if ( ! empty( $formatted_users ) ) {
					$sitename = sanitize_key( get_bloginfo( 'name' ) );
					$sitename .= ( ! empty( $sitename ) ) ? '-' : '';
					$filename = $sitename . 'wishlist-users-' . sanitize_title_with_dashes( $product->get_title() ) . '-' . gmdate( 'Y-m-d-H-i' ) . '.csv';

					// Add Labels to CSV.
					$formatted_users_labels[] = array(
						__( 'User ID', 'aj-woocommerce-wishlist' ),
						__( 'User Email', 'aj-woocommerce-wishlist' ),
						__( 'User First Name', 'aj-woocommerce-wishlist' ),
						__( 'User Last Name', 'aj-woocommerce-wishlist' ),
					);

					$formatted_users = array_merge( $formatted_users_labels, $formatted_users );

					header( 'Content-Description: File Transfer' );
					header( 'Content-Disposition: attachment; filename=' . $filename );
					header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

					$df = fopen( 'php://output', 'w' );

					foreach ( $formatted_users as $row ) {
						fputcsv( $df, $row );
					}

					fclose( $df );
				}
			}

			die();
		}

		/* === WISHLIST COUNT PRODUCT COLUMN === */

		/**
		 * Add column to product table, to show product occurrences in wishlists
		 *
		 * @param array $columns Array of columns for products table.
		 * @return array
		 * @since 2.0.0
		 */
		public function add_product_columns( $columns ) {
			$columns['wishlist_count'] = __( 'Wishlist Count', 'aj-woocommerce-wishlist' );
			return $columns;
		}

		/**
		 * Render column of occurrences in product table
		 *
		 * @param string $column Column to render.
		 * @return void
		 * @since 2.0.0
		 */
		public function render_product_columns( $column ) {
			global $post;

			if ( 'wishlist_count' == $column ) {
				echo AJ_WCWL()->count_product_occurrences( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Register column of occurrences in wishlist as sortable
		 *
		 * @param array $columns Columns that can be sorted in product list table.
		 * @return array
		 * @since 2.0.0
		 */
		public function product_sortable_columns( $columns ) {
			$columns['wishlist_count'] = 'wishlist_count';
			return $columns;
		}

		/**
		 * Alter post query when ordering for wishlist occurrences
		 *
		 * @param array $vars Arguments used to filter products for the table.
		 * @return array
		 * @since 2.0.0
		 */
		public function product_request_query( $vars ) {
			global $typenow, $wp_query;

			if ( 'product' === $typenow ) {
				// Sorting.
				if ( isset( $vars['orderby'] ) ) {
					if ( 'wishlist_count' == $vars['orderby'] ) {
						add_filter( 'posts_join', array( 'AJ_WCWL_Wishlist_Item_Data_Store', 'filter_join_for_wishlist_count' ) );
						add_filter( 'posts_orderby', array( 'AJ_WCWL_Wishlist_Item_Data_Store', 'filter_orderby_for_wishlist_count' ) );
					}
				}
			}

			return $vars;
		}

		/* === SEND PROMOTION EMAIL === */

		/**
		 * Preview promotional email template
		 *
		 * @param bool $return Whether to return or echo the result (@since 3.0.0).
		 *
		 * @return string
		 * @since 2.0.7
		 */
		public function preview_promotion_email( $return = false ) {
			$template = ( isset( $_REQUEST['template'] ) && in_array( $_REQUEST['template'], array( 'html', 'plain' ) ) ) ? $_REQUEST['template'] : 'html'; // phpcs:ignore WordPress.Security
			$product_id = isset( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : false; // phpcs:ignore WordPress.Security
			$content_html = isset( $_REQUEST['content_html'] ) ? wp_kses_post( wp_unslash( $_REQUEST['content_html'] ) ) : false;
			$content_text = isset( $_REQUEST['content_text'] ) ? sanitize_textarea_field( wp_unslash( $_REQUEST['content_text'] ) ) : false;
			$coupon = isset( $_REQUEST['coupon'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['coupon'] ) ) : false;
			$template_path = '';

			if ( is_array( $product_id ) ) {
				$product_id = array_shift( $product_id );
			}

			$product_id = intval( $product_id );

			if ( 'plain' == $template ) {
				$template_path = 'plain/';
			}

			// load the mailer class.
			$mailer = WC()->mailer();
			$email = $mailer->emails['AJ_WCWL_Promotion_Email'];
			$email->user = get_user_by( 'id', get_current_user_id() );
			$email->object = wc_get_product( $product_id );

			// set contents.
			if ( $content_html ) {
				$email->content_html = wpautop( $content_html );
			}
			if ( $content_text ) {
				$email->content_text = $content_text;
			}

			// set coupon.
			if ( $coupon ) {
				$email->coupon = new WC_Coupon( $coupon );
			}

			// get the preview email subject.
			$email_heading = $email->get_heading();
			$email_content = $email->{'get_custom_content_' . $template}();

			// get the preview email content.
			ob_start();
			include( AJ_WCWL_DIR . 'templates/emails/' . $template_path . 'promotion.php' );
			$message = ob_get_clean();

			if ( 'plain' == $template ) {
				$message = nl2br( $message );
			}

			$message = $email->style_inline( $message );

			// print the preview email.
			if ( $return ) {
				return $message;
			}

			echo $message; // phpcs:ignore WordPress.Security
		}

		/**
		 * Preview promotion email on ajax call
		 *
		 * @return void
		 * @since 3.0.0
		 */
		public function ajax_preview_promotion_email() {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				die;
			}

			$this->preview_promotion_email();
			die;
		}

		/**
		 * Calculate the number of receivers for the current email and echo it as json content
		 *
		 * @return void
		 */
		public function ajax_calculate_promotion_email_receivers() {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				die;
			}

			$product_id = isset( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : false; // phpcs:ignore WordPress.Security
			$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : false; // phpcs:ignore WordPress.Security
			$count = 0;

			$user_id = is_array( $user_id ) ? array_filter( $user_id ) : $user_id;
			$product_id = is_array( $product_id ) ? array_filter( $product_id ) : $product_id;

			if ( $user_id ) {
				$count = is_array( $user_id ) ? count( $user_id ) : 1;
			} else {
				$receivers_ids = array();
				$product_id = is_array( $product_id ) ? $product_id : (array) $product_id;
				$product_id = array_map( 'intval', $product_id );

				foreach ( $product_id as $id ) {
					$items = AJ_WCWL_Wishlist_Factory::get_wishlist_items(
						array(
							'wishlist_id' => 'all',
							'session_id' => false,
							'user_id' => false,
							'product_id' => $id,
						)
					);

					if ( ! empty( $items ) ) {
						foreach ( $items as $item ) {
							$receivers_ids[] = $item->get_user_id();
						}
					}

					$receivers_ids = array_unique( $receivers_ids );
					$count += count( $receivers_ids );
				}
			}

			wp_send_json(
				array(
					'count' => $count,
					'label' => sprintf( '%d %s', $count, _n( 'user', 'users', $count, 'aj-woocommerce-wishlist' ) ),
				)
			);
		}

		/**
		 * Trigger event to send the promotion email
		 *
		 * @return void
		 * @since 2.0.7
		 */
		public function trigger_promotion_email() {
			if ( ! isset( $_POST['send_promotion_email'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['send_promotion_email'] ) ), 'send_promotion_email_action' ) ) {
				return;
			}

			if ( ! isset( $_POST['product_id'] ) && ! isset( $_POST['user_id'] ) ) {
				return;
			}

			$product_id = isset( $_POST['product_id'] ) ? $_POST['product_id'] : false; // phpcs:ignore WordPress.Security
			$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : false; // phpcs:ignore WordPress.Security
			$html_content = isset( $_POST['content_html'] ) ? wp_kses_post( wp_unslash( $_POST['content_html'] ) ) : false;
			$text_content = isset( $_POST['content_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['content_text'] ) ) : false;
			$coupon_code = isset( $_POST['coupon'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon'] ) ) : false;
			$receivers_ids = array();

			$product_id = is_array( $product_id ) ? $product_id : (array) $product_id;
			$product_id = array_filter( array_map( 'intval', $product_id ) );

			$user_id = is_array( $user_id ) ? $user_id : (array) $user_id;
			$user_id = array_filter( array_map( 'intval', $user_id ) );

			// if we're saving draft, update option and skip.
			if ( isset( $_POST['save_draft'] ) ) {
				update_option(
					'aj_wcwl_promotion_draft',
					array(
						'product_id'   => $product_id,
						'user_id'      => $user_id,
						'content_html' => $html_content,
						'content_text' => $text_content,
						'coupon'       => $coupon_code,
					)
				);

				wp_redirect(
					esc_url_raw(
						add_query_arg(
							array(
								'page'       => 'aj_wcwl_panel',
								'tab'        => 'popular',
								'action'     => $user_id ? 'show_users' : false,
								'product_id' => $user_id ? array_shift( $product_id ) : false,
							),
							admin_url( 'admin.php' )
						)
					)
				);
				exit;
			}

			if ( ! empty( $user_id ) ) {
				$receivers_ids = $user_id;
			} elseif ( ! empty( $product_id ) ) {
				foreach ( $product_id as $id ) {
					$items = AJ_WCWL_Wishlist_Factory::get_wishlist_items(
						array(
							'wishlist_id' => 'all',
							'session_id' => false,
							'user_id' => false,
							'product_id' => $id,
						)
					);

					if ( ! empty( $items ) ) {
						foreach ( $items as $item ) {
							$receivers_ids[] = $item->get_user_id();
						}
					}
				}

				$receivers_ids = array_unique( $receivers_ids );
			}

			if ( ! empty( $receivers_ids ) ) {
				$campaign_info = apply_filters(
					'aj_wcwl_promotional_email_additional_info',
					array(
						'html_content'  => $html_content,
						'text_content'  => $text_content,
						'coupon_code'   => $coupon_code,
						'product_id'    => $product_id,
						'user_id'       => $user_id,
						'receivers'     => $receivers_ids,
						'schedule_date' => time(),
						'counters'      => array(
							'sent'      => 0,
							'to_send'   => count( $receivers_ids ),
						),
					)
				);
				// retrieve campaign queue.
				$queue   = get_option( 'aj_wcwl_promotion_campaign_queue', array() );
				$queue[] = $campaign_info;
				$res     = update_option( 'aj_wcwl_promotion_campaign_queue', $queue );
			} else {
				$res = false;
			}

			wp_redirect(
				esc_url_raw(
					add_query_arg(
						array(
							'page'       => 'aj_wcwl_panel',
							'tab'        => 'popular',
							'email_sent' => ! empty( $res ) ? 'true' : 'false',
							'action'     => $user_id ? 'show_users' : false,
							'product_id' => $user_id ? array_shift( $product_id ) : false,
						),
						admin_url( 'admin.php' )
					)
				)
			);
			exit;
		}

		/**
		 * Delete promotional email draft
		 *
		 * @return void
		 */
		public function delete_promotion_draft() {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
				delete_option( 'aj_wcwl_promotion_draft' );
			}

			wp_redirect(
				esc_url_raw(
					add_query_arg(
						array(
							'page' => 'aj_wcwl_panel',
							'tab'  => 'popular',
						),
						admin_url( 'admin.php' )
					)
				)
			);
		}

		/* === AJ WOOCOMMERCE EMAIL TEMPLATES INTEGRATION === */

		/**
		 * Filters email template available on aj-wcet
		 *
		 * @param mixed $templates Currently available templates.
		 * @return mixed Fitlered templates
		 * @since 2.0.13
		 */
		public function register_emails_for_custom_templates( $templates ) {
			$templates[] = array(
				'id'        => 'aj-wcwl-ask-an-estimate-mail',
				'name'      => __( 'Wishlist "Ask an estimate"', 'aj-woocommerce-wishlist' ),
			);
			$templates[] = array(
				'id'        => 'aj-wcwl-promotion-mail',
				'name'      => __( 'Wishlist Promotion', 'aj-woocommerce-wishlist' ),
			);

			return $templates;
		}

		/* === ADMIN ONLY AJAX === */

		/**
		 * Returns coupons upon search
		 *
		 * @param string $term String to match; if nothing is passed, it will be retrieved from query string.
		 * @return void
		 * @since 3.0.0
		 */
		public function json_search_coupons( $term = '' ) {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				die;
			}

			check_ajax_referer( 'search-products', 'security' );

			if ( empty( $term ) && isset( $_GET['term'] ) ) {
				$term = (string) sanitize_text_field( wp_unslash( $_GET['term'] ) );
			}

			if ( empty( $term ) ) {
				wp_die();
			}

			if ( ! empty( $_GET['limit'] ) ) {
				$limit = absint( $_GET['limit'] );
			} else {
				$limit = absint( apply_filters( 'woocommerce_json_search_limit', 30 ) );
			}

			$include_ids = ! empty( $_GET['include'] ) ? array_map( 'absint', (array) wp_unslash( $_GET['include'] ) ) : array();
			$exclude_ids = ! empty( $_GET['exclude'] ) ? array_map( 'absint', (array) wp_unslash( $_GET['exclude'] ) ) : array();

			$coupons = array();
			$coupon_objects = array();
			$ids = get_posts(
				array(
					's' => $term,
					'post_type' => 'shop_coupon',
					'posts_per_page' => $limit,
					'post__in' => $include_ids,
					'post__not_id' => $exclude_ids,
					'fields' => 'ids',
				)
			);

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $coupon_id ) {
					$coupon_objects[] = new WC_Coupon( $coupon_id );
				}
			}

			foreach ( $coupon_objects as $coupon_object ) {
				$formatted_name = $coupon_object->get_code();

				$coupons[ $formatted_name ] = rawurldecode( $formatted_name );
			}

			wp_send_json( apply_filters( 'woocommerce_json_search_found_coupons', $coupons ) );
		}

		/* === ADMIN ONLY AJAX === */
	}
}

/**
 * Unique access to instance of AJ_WCWL_Admin_Premium class
 *
 * @return \AJ_WCWL_Admin_Premium
 * @since 2.0.0
 */
function AJ_WCWL_Admin_Premium() {
	return AJ_WCWL_Admin_Premium::get_instance();
}
