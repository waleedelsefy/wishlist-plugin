<?php
/**
 * Define constants and include Plugin Framework files.
 *
 * @package AJ\PluginFramework
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


! defined( 'DIDO_CORE_PLUGIN' ) && define( 'DIDO_CORE_PLUGIN', true );
! defined( 'DIDO_CORE_PLUGIN_PATH' ) && define( 'DIDO_CORE_PLUGIN_PATH', dirname( __FILE__ ) );
! defined( 'DIDO_CORE_PLUGIN_URL' ) && define( 'DIDO_CORE_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
! defined( 'DIDO_CORE_PLUGIN_TEMPLATE_PATH' ) && define( 'DIDO_CORE_PLUGIN_TEMPLATE_PATH', DIDO_CORE_PLUGIN_PATH . '/templates' );

require_once 'dido-functions.php';
require_once 'dido-woocommerce-compatibility.php';
require_once 'dido-plugin-registration-hook.php';
require_once 'includes/class-dido-metabox.php';
require_once 'includes/class-dido-plugin-panel.php';
require_once 'includes/class-dido-plugin-panel-woocommerce.php';
require_once 'includes/class-dido-ajax.php';
require_once 'includes/class-dido-plugin-subpanel.php';
require_once 'includes/class-dido-plugin-common.php';
require_once 'includes/class-dido-gradients.php';
require_once 'includes/class-dido-plugin-licence.php';
require_once 'includes/class-dido-theme-licence.php';
require_once 'includes/class-dido-video.php';
require_once 'includes/class-dido-upgrade.php';
require_once 'includes/class-dido-pointers.php';
require_once 'includes/class-dido-icons.php';
require_once 'includes/class-dido-assets.php';
require_once 'includes/class-aj-debug.php';
require_once 'includes/class-aj-dashboard.php';
require_once 'includes/privacy/class-aj-privacy.php';
require_once 'includes/privacy/class-aj-privacy-plugin-abstract.php';
require_once 'includes/promo/aj-promo.php';
require_once 'includes/class-aj-system-status.php';

// Gutenberg Support.
if ( class_exists( 'WP_Block_Type_Registry' ) ) {
	require_once 'includes/class-aj-gutenberg.php';
}
// load from theme folder...
load_textdomain( 'aj-plugin-fw', get_template_directory() . '/core/plugin-fw/aj-plugin-fw-' . apply_filters( 'plugin_locale', get_locale(), 'aj-plugin-fw' ) . '.mo' ) ||
// ...or from plugin folder.
load_textdomain( 'aj-plugin-fw', dirname( __FILE__ ) . '/languages/aj-plugin-fw-' . apply_filters( 'plugin_locale', get_locale(), 'aj-plugin-fw' ) . '.mo' );

add_filter( 'plugin_row_meta', 'dido_plugin_fw_row_meta', 20, 4 );

if ( ! function_exists( 'dido_plugin_fw_row_meta' ) ) {
	/**
	 * Show the plugin row meta.
	 *
	 * @param string[] $plugin_meta An array of the plugin's metadata, including the version, author, author URI, and plugin URI.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array    $plugin_data An array of plugin data.
	 * @param string   $status      Status filter currently applied to the plugin list.
	 *
	 * @return string[] array of the plugin's metadata.
	 * @author Andrea Grillo <andrea.grillo@ajemes.com>
	 * @since  3.0.17
	 */
	function dido_plugin_fw_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		$base_uri = array(
			'live_demo'       => 'https://plugins.ajemes.com/',
			'documentation'   => 'https://docs.ajemes.com/',
			'premium_support' => 'https://ajemes.com/my-account/support/dashboard/',
			'free_support'    => 'https://wordpress.org/support/plugin/',
			'premium_version' => 'https://ajemes.com/themes/plugins/',
		);

		$default = array(
			'live_demo'       => array(
				'label' => _x( 'Live Demo', 'Plugin Row Meta', 'aj-plugin-fw' ),
				'icon'  => 'dashicons  dashicons-laptop',
			),
			'documentation'   => array(
				'label' => _x( 'Documentation', 'Plugin Row Meta', 'aj-plugin-fw' ),
				'icon'  => 'dashicons  dashicons-search',
			),
			'support'         => array(
				'label' => _x( 'Support', 'Plugin Row Meta', 'aj-plugin-fw' ),
				'icon'  => 'dashicons  dashicons-admin-users',
			),
			'premium_version' => array(
				'label' => _x( 'Premium version', 'Plugin Row Meta', 'aj-plugin-fw' ),
				'icon'  => 'dashicons  dashicons-cart',
			),
		);

		$to_show           = array( 'live_demo', 'documentation', 'support', 'premium_version' );
		$new_row_meta_args = apply_filters(
			'aj_show_plugin_row_meta',
			array(
				'to_show' => $to_show,
				'slug'    => '',
			),
			$plugin_meta,
			$plugin_file,
			$plugin_data,
			$status
		);
		$fields            = isset( $new_row_meta_args['to_show'] ) ? $new_row_meta_args['to_show'] : array();
		$slug              = isset( $new_row_meta_args['slug'] ) ? $new_row_meta_args['slug'] : '';
		$is_premium        = isset( $new_row_meta_args['is_premium'] ) ? $new_row_meta_args['is_premium'] : '';

		if ( ! ! $is_premium ) {
			$to_remove = array_search( 'premium_version', $fields, true );

			if ( false !== $to_remove ) {
				unset( $fields[ $to_remove ] );
			}
		}

		foreach ( $fields as $field ) {
			$row_meta = isset( $new_row_meta_args[ $field ] ) ? wp_parse_args( $new_row_meta_args[ $field ], $default[ $field ] ) : $default[ $field ];
			$url      = '';
			$icon     = '';
			$label    = '';

			// Check for Label.
			if ( isset( $row_meta['label'] ) ) {
				$label = $row_meta['label'];
			}

			// Check for Icon.
			if ( isset( $row_meta['icon'] ) ) {
				$icon = $row_meta['icon'];
			}

			// Check for URL.
			if ( isset( $row_meta['url'] ) ) {
				$url = $row_meta['url'];
			} else {
				if ( ! empty( $slug ) ) {
					if ( 'support' === $field ) {
						$support_field = true === $is_premium ? 'premium_support' : 'free_support';
						if ( ! empty( $base_uri[ $support_field ] ) ) {
							$url = $base_uri[ $support_field ];
						}

						if ( 'free_support' === $support_field ) {
							$url = $url . $slug;
						}
					} else {
						if ( isset( $base_uri[ $field ] ) ) {
							$url = apply_filters( "aj_plugin_row_meta_{$field}_url", $base_uri[ $field ] . $slug, $field, $slug, $base_uri );
						}
					}
				}
			}

			if ( ! empty( $url ) && ! empty( $label ) ) {
				$url           = trailingslashit( $url );
				$plugin_meta[] = sprintf( '<a href="%s" target="_blank"><span class="%s"></span>%s</a>', $url, $icon, $label );
			}
		}

		// Author Name Hack.
		$plugin_meta = preg_replace( '/>AJEMES</', '>AJ<', $plugin_meta );

		return $plugin_meta;
	}
}

if ( ! function_exists( 'aj_add_action_links' ) ) {
	/**
	 * Add the action links to plugin admin page
	 *
	 * @param array  $links       The plugin links.
	 * @param string $panel_page  The panel page.
	 * @param bool   $is_premium  Is this plugin premium? True if the plugin is premium. False otherwise.
	 * @param string $plugin_slug The plugin slug.
	 *
	 * @return   array
	 * @author   Andrea Grillo <andrea.grillo@ajemes.com>
	 * @since    1.6.5
	 */
	function aj_add_action_links( $links, $panel_page = '', $is_premium = false, $plugin_slug = '' ) {
		$links = is_array( $links ) ? $links : array();
		if ( ! empty( $panel_page ) ) {
			$links[] = sprintf( '<a href="%s">%s</a>', admin_url( "admin.php?page={$panel_page}" ), _x( 'Settings', 'Action links', 'aj-plugin-fw' ) );
		}

		if ( $is_premium && class_exists( 'DIDO_Plugin_Licence' ) ) {
			$links[] = sprintf( '<a href="%s">%s</a>', DIDO_Plugin_Licence()->get_license_activation_url( $plugin_slug ), __( 'License', 'aj-plugin-fw' ) );
		}

		return $links;
	}
}
