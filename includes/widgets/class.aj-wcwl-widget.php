<?php
/**
 * Widget that shows all user lists
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( !defined( 'AJ_WCWL' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'AJ_WCWL_Widget' ) ) {
	/**
	 * WooCommerce Wishlist Widget
	 *
	 * @since 1.0.0
	 */
	class AJ_WCWL_Widget extends WP_Widget {

		/**
		 * Sets up the widgets
		 */
		public function __construct(){
			parent::__construct(
				'aj-wcwl-lists',
				__( 'AJ Wishlist Lists', 'aj-woocommerce-wishlist' ),
				array( 'description' => __( 'A list of all the user\'s wishlists', 'aj-woocommerce-wishlist' ) )
			);
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			$create_page_title = get_option( 'aj_wcwl_wishlist_create_title' );
			$manage_page_title = get_option( 'aj_wcwl_wishlist_manage_title' );
			$search_page_title = get_option( 'aj_wcwl_wishlist_search_title' );
			$default_wishlist_title = get_option( 'aj_wcwl_wishlist_title' );

			$current_wishlist = AJ_WCWL_Wishlist_Factory::get_current_wishlist();
			$active = AJ_WCWL()->get_current_endpoint();

			$instance['item'] = __CLASS__;
			$instance['unique_id'] = isset( $instance['unique_id'] ) ? $instance['unique_id'] : uniqid();
			$instance['ajax_loading'] = isset( $instance['ajax_loading'] ) ? $instance['ajax_loading'] : 'yes' == get_option( 'aj_wcwl_ajax_enable', 'no' );

			$fragment_options = AJ_WCWL_Frontend()->format_fragment_options( $instance );

			$additional_info = array(
				'wishlist_url' => AJ_WCWL()->get_wishlist_url(),
				'instance' => $instance,
				'fragment_options' => $fragment_options,
				'users_wishlists' => AJ_WCWL()->get_current_user_wishlists(),
				'multi_wishlist_enabled' => AJ_WCWL()->is_multi_wishlist_enabled(),
				'default_wishlist_title' => $default_wishlist_title,
				'create_page_title' => $create_page_title,
				'manage_page_title' => $manage_page_title,
				'search_page_title' => $search_page_title,
				'current_wishlist' => $current_wishlist,
				'active' => $active
			);

			$args = array_merge( $args, $additional_info );

			aj_wcwl_get_template( 'wishlist-widget-lists.php', $args );
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			$show_create_link = ( isset( $instance['show_create_link'] ) && $instance['show_create_link'] == 'yes' );
			$show_search_link = ( isset( $instance['show_search_link'] ) && $instance['show_search_link'] == 'yes' );
			$show_manage_link = ( isset( $instance['show_manage_link'] ) && $instance['show_manage_link'] == 'yes' );
			$title = isset( $instance['title'] ) ?  $instance['title'] : '';
			$wishlist_link = isset( $instance['wishlist_link'] ) ?  $instance['wishlist_link'] : '';
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'aj-woocommerce-wishlist' )?></label>
				<input class="widefat"  id="<?php echo $this->get_field_id( 'title' )?>" name="<?php echo $this->get_field_name( 'title' ) ?>" type="text" value="<?php echo $title ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'wishlist_link' ); ?>"><?php _e( '"Your wishlist" link:', 'aj-woocommerce-wishlist' )?></label>
				<input class="widefat"  id="<?php echo $this->get_field_id( 'wishlist_link' )?>" name="<?php echo $this->get_field_name( 'wishlist_link' ) ?>" type="text" value="<?php echo $wishlist_link ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_create_link' ); ?>">
					<input id="<?php echo $this->get_field_id( 'show_create_link' )?>" name="<?php echo $this->get_field_name( 'show_create_link' ) ?>" type="checkbox" value="yes" <?php checked( $show_create_link ) ?> />
					<?php _e( 'Show create link', 'aj-woocommerce-wishlist' ); ?>
				</label><br/>
				<label for="<?php echo $this->get_field_id( 'show_search_link' ); ?>">
					<input id="<?php echo $this->get_field_id( 'show_search_link' )?>" name="<?php echo $this->get_field_name( 'show_search_link' ) ?>" type="checkbox" value="yes" <?php checked( $show_search_link ) ?> />
					<?php _e( 'Show search link', 'aj-woocommerce-wishlist' ); ?>
				</label><br/>
				<label for="<?php echo $this->get_field_id( 'show_manage_link' ); ?>">
					<input id="<?php echo $this->get_field_id( 'show_manage_link' )?>" name="<?php echo $this->get_field_name( 'show_manage_link' ) ?>" type="checkbox" value="yes" <?php checked( $show_manage_link ) ?> />
					<?php _e( 'Show manage link', 'aj-woocommerce-wishlist' ); ?>
				</label>
			</p>
		<?php
		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['wishlist_link'] = ( ! empty( $new_instance['wishlist_link'] ) ) ? strip_tags( $new_instance['wishlist_link'] ) : '';
			$instance['show_create_link'] = ( isset( $new_instance['show_create_link'] ) ) ? 'yes' : 'no';
			$instance['show_search_link'] = ( isset( $new_instance['show_search_link'] ) ) ? 'yes' : 'no';
			$instance['show_manage_link'] = ( isset( $new_instance['show_manage_link'] ) ) ? 'yes' : 'no';

			return $instance;
		}
	}
}