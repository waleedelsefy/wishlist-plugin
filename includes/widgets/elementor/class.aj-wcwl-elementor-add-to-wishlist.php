<?php
/**
 * Add to Wishlist widget for Elementor
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 3.0.7
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if( ! class_exists( 'AJ_WCWL_Elementor_Add_to_Wishlist' ) ) {
	class AJ_WCWL_Elementor_Add_to_Wishlist extends \Elementor\Widget_Base {

		/**
		 * Get widget name.
		 *
		 * Retrieve AJ_WCWL_Elementor_Add_to_Wishlist widget name.
		 *
		 * @return string Widget name.
		 * @since  1.0.0
		 * @access public
		 */
		public function get_name() {
			return 'aj_wcwl_add_to_wishlist';
		}

		/**
		 * Get widget title.
		 *
		 * Retrieve AJ_WCWL_Elementor_Add_to_Wishlist widget title.
		 *
		 * @return string Widget title.
		 * @since  1.0.0
		 * @access public
		 */
		public function get_title() {
			return _x( 'AJ Wishlist Add button', 'Elementor widget name', 'aj-woocommerce-wishlist' );
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve AJ_WCWL_Elementor_Add_to_Wishlist widget icon.
		 *
		 * @return string Widget icon.
		 * @since  1.0.0
		 * @access public
		 */
		public function get_icon() {
			return 'eicon-button';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the AJ_WCWL_Elementor_Add_to_Wishlist widget belongs to.
		 *
		 * @return array Widget categories.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_categories() {
			return [ 'general', 'aj' ];
		}

		/**
		 * Register AJ_WCWL_Elementor_Add_to_Wishlist widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function _register_controls() {

			$this->start_controls_section(
				'product_section',
				[
					'label' => _x( 'Product', 'Elementor section title', 'aj-woocommerce-wishlist' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'product_id',
				[
					'label'       => _x( 'Product ID', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::NUMBER,
					'input_type'  => 'text',
					'placeholder' => '123',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'labels_section',
				[
					'label' => _x( 'Labels', 'Elementor section title', 'aj-woocommerce-wishlist' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'label',
				[
					'label'       => _x( 'Button label', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Add to wishlist', 'aj-woocommerce-wishlist' ),
				]
			);

			$this->add_control(
				'browse_wishlist_text',
				[
					'label'       => _x( '"Browse wishlist" label', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Browse wishlist', 'aj-woocommerce-wishlist' ),
				]
			);

			$this->add_control(
				'already_in_wishslist_text',
				[
					'label'       => _x( '"Product already in wishlist" label', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Product already in wishlist', 'aj-woocommerce-wishlist' ),
				]
			);

			$this->add_control(
				'product_added_text',
				[
					'label'       => _x( '"Product added to wishlist" label', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Product added to wishlist', 'aj-woocommerce-wishlist' ),
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'advanced_section',
				[
					'label' => _x( 'Advanced', 'Elementor section title', 'aj-woocommerce-wishlist' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'wishlist_url',
				[
					'label'       => _x( 'URL of the wishlist page', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'url',
					'placeholder' => '',
				]
			);

			$this->add_control(
				'icon',
				[
					'label'       => _x( 'Icon for the button', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => '',
				]
			);

			$this->add_control(
				'link_classes',
				[
					'label'       => _x( 'Additional CSS classes for the button', 'Elementor control label', 'aj-woocommerce-wishlist' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => '',
				]
			);

			$this->end_controls_section();

		}

		/**
		 * Render AJ_WCWL_Elementor_Add_to_Wishlist widget output on the frontend.
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function render() {

			$attribute_string = '';
			$settings         = $this->get_settings_for_display();

			foreach ( $settings as $key => $value ) {
				if ( empty( $value ) || ! is_scalar( $value ) ) {
					continue;
				}
				$attribute_string .= " {$key}=\"{$value}\"";
			}

			echo do_shortcode( "[aj_wcwl_add_to_wishlist {$attribute_string}]" );
		}

	}
}