<?php
/**
 * Wishlist Cron Handler
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'AJ_WCWL_Cron' ) ) {
	/**
	 * This class handles cron for wishlist plugin
	 *
	 * @since 3.0.0
	 */
	class AJ_WCWL_Cron {
		/**
		 * Array of events to schedule
		 *
		 * @var array
		 */
		protected $_crons = array();

		/**
		 * Single instance of the class
		 *
		 * @var \AJ_WCWL_Cron
		 * @since 3.0.0
		 */
		protected static $instance;

		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'schedule' ) );
		}

		/**
		 * Returns registered crons
		 *
		 * @return array Array of registered crons ans callbacks
		 */
		public function get_crons() {
			if( empty( $this->_crons ) ){
				$this->_crons = array(
					'aj_wcwl_delete_expired_wishlists' => array(
						'schedule' => 'daily',
						'callback' => array( $this, 'delete_expired_wishlists' )
					)
				);
			}

			return apply_filters( 'aj_wcwl_crons', $this->_crons );
		}

		/**
		 * Schedule events not scheduled yet; register callbacks for each event
		 *
		 * @return void
		 */
		public function schedule() {
			$crons = $this->get_crons();

			if( ! empty( $crons ) ){
				foreach( $crons as $hook => $data ){

					add_action( $hook, $data['callback'] );

					if( ! wp_next_scheduled( $hook ) ){
						wp_schedule_event( time() + MINUTE_IN_SECONDS, $data['schedule'], $hook );
					}
				}
			}
		}

		/**
		 * Delete expired session wishlist
		 *
		 * @return void
		 */
		public function delete_expired_wishlists() {
			try{
				WC_Data_Store::load( 'wishlist' )->delete_expired();
			}
			catch( Exception $e ){
				return;
			}
		}

		/**
		 * Returns single instance of the class
		 *
		 * @return \AJ_WCWL_Cron
		 * @since 3.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

/**
 * Unique access to instance of AJ_WCWL_Cron class
 *
 * @return \AJ_WCWL_Cron
 * @since 3.0.0
 */
function AJ_WCWL_Cron(){
	return defined( 'AJ_WCWL_PREMIUM' ) ? AJ_WCWL_Cron_Premium::get_instance() : AJ_WCWL_Cron::get_instance();
}