<?php
/**
 * This file belongs to the DIDO Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

return apply_filters( 'aj_wcwl_ask_an_estimate_options', array(
	'ask_an_estimate' => array(
		'aj_ask_an_estimate_start' => array(
			'name' => __( 'Ask for an estimate', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_ask_an_estimate'
		),

		'enable_ask_an_estimate' => array(
			'name'      => __( 'Enable "Ask for an estimate" button', 'aj-woocommerce-wishlist' ),
			'desc'      => sprintf(
				'%s. %s <a href="%s">%s</a>',
				__( 'Shows "Ask for an estimate" button on Wishlist page', 'aj-woocommerce-wishlist' ),
				__( 'If you want to customize the email that will be sent to the admin, please, visit', 'aj-woocommerce-wishlist' ),
				add_query_arg( array( 'page' => 'wc-settings', 'tab' => 'email', 'section' => 'aj_wcwl_estimate_email' ), admin_url( 'admin.php' ) ),
				__( 'Settings Page', 'aj-woocommerce-wishlist' ) ),
			'id'        => 'aj_wcwl_show_estimate_button',
			'default'   => 'yes',
			'type'      => 'aj-field',
			'aj-type' => 'onoff',
		),

		'ask_an_estimate_mail_type' => array(
			'name'      => __( 'Email type', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose which type of email to send', 'aj-woocommerce-wishlist' ),
			'id'        => 'woocommerce_estimate_mail_settings[email_type]',
			'default'   => 'html',
			'type'      => 'aj-field',
			'aj-type' => 'select',
			'class'     => 'wc-enhanced-select',
			'options'   => array(
				'plain'     => __( 'Plain', 'aj-woocommerce-wishlist' ),
				'html'      => __( 'HTML', 'aj-woocommerce-wishlist' ),
				'multipart' => __( 'Multipart', 'aj-woocommerce-wishlist' ),
			),
		),

		'ask_an_estimate_mail_heading' => array(
			'name'      => __( 'Email heading', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Enter the title for your email notification. Leave blank to use the default heading: "<i>Estimate request</i>"', 'aj-woocommerce-wishlist' ),
			'id'        => 'woocommerce_estimate_mail_settings[heading]',
			'default'   => '',
			'type'      => 'text',
		),

		'ask_an_estimate_mail_subject' => array(
			'name'      => __( 'Email subject', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Enter the mail subject line. Leave blank to use the default subject: "<i>[Estimate request]</i>"', 'aj-woocommerce-wishlist' ),
			'id'        => 'woocommerce_estimate_mail_settings[subject]',
			'default'   => '',
			'type'      => 'text',
		),

		'ask_an_estimate_mail_recipients' => array(
			'name'      => __( 'Recipient(s)', 'aj-woocommerce-wishlist' ),
			'desc'      => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to "<i>%s</i>"', 'aj-woocommerce-wishlist' ), get_option( 'woocommerce_email_from_address' ) ),
			'id'        => 'woocommerce_estimate_mail_settings[recipient]',
			'default'   => '',
			'type'      => 'aj-field',
			'aj-type' => 'textarea'
		),

		'ask_an_estimate_send_cc' => array(
			'name'      => __( 'Send CC option', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Allow the admin to choose whether to send a copy of the email to the user', 'aj-woocommerce-wishlist' ),
			'id'        => 'woocommerce_estimate_mail_settings[enable_cc]',
			'default'   => 'no',
			'type'      => 'aj-field',
			'aj-type' => 'onoff',
		),

		'aj_ask_an_estimate_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_ask_an_estimate'
		),

		'ask_an_estimate_fields_section' => array(
			'name' => __( 'Additional Popup', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => __( 'These fields will be shown in the popup opened by Ask an Estimate button. The Email field will be prepended for unauthenticated users. An Additional Notes textarea will be postponed to the selected fields.', 'aj-woocommerce-wishlist' ),
			'id'   => 'aj_wcwl_additional_fields_settings'
		),

		'enable_ask_an_estimate_additional_info' => array(
			'name'      => __( 'Enable "Additional notes" popup', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Show an Additional notes popup before submitting the price estimate request to let customers add extra notes', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_show_additional_info_textarea',
			'default'   => 'no',
			'type'      => 'aj-field',
			'aj-type' => 'onoff',
			'deps'      => array(
				'id' => 'aj_wcwl_show_estimate_button',
				'value' => 'yes'
			)
		),

		'ask_an_estimate_fields' => array(
			'id'               => 'aj_wcwl_ask_an_estimate_fields',
			'name'             => __( 'Ask for an estimate fields', 'aj-woocommerce-wishlist' ),
			'type'             => 'aj-field',
			'aj-type'        => 'toggle-element',
			'add_button'       => __( 'Add new field', 'aj-woocommerce-wishlist' ), //optional
			'aj-display-row' => false,
			'title'            => __( 'Field %%label%%', 'aj-woocommerce-wishlist' ),
			'value'            => '',
			'elements'         => array(
				'label' => array(
					'id'        => 'label',
					'name'      => __( 'Label for the field', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Enter the label that will be shown for this field', 'aj-woocommerce-wishlist' ),
					'type'      => 'text',
				),
				'required' => array(
					'id'        => 'required',
					'name'      => __( 'Required field', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Choose whether this field is required or not', 'aj-woocommerce-wishlist' ),
					'type'      => 'aj-field',
					'aj-type' => 'onoff'
				),
				'placeholder'   => array(
					'id'        => 'placeholder',
					'name'      => __( 'Placeholder for the field', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Enter the placeholder that will be shown in the field', 'aj-woocommerce-wishlist' ),
					'type'      => 'text',
				),
				'description' => array(
					'id'        => 'description',
					'name'      => __( 'Field description', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Enter the description that will be shown above the field', 'aj-woocommerce-wishlist' ),
					'type'      => 'aj-field',
					'aj-type' => 'textarea'
				),
				'position' => array(
					'id'        => 'position',
					'name'      => __( 'Position of the field in the form', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Choose between first (the field will be the first in a row that contains two items), last (the field will be the second in a row of two) or wide (the field will take an entire row)', 'aj-woocommerce-wishlist' ),
					'type'      => 'select',
					'class'     => 'wc-enhanced-select',
					'options'   => array(
						'first' => __( 'First', 'aj-woocommerce-wishlist' ),
						'last'  => __( 'Last', 'aj-woocommerce-wishlist' ),
						'wide'  => __( 'Wide', 'aj-woocommerce-wishlist' )
					)
				),
				'type' => array(
					'id'        => 'type',
					'name'      => __( 'Type of field', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Choose the type of field to print in the form', 'aj-woocommerce-wishlist' ),
					'type'      => 'select',
					'class'     => 'wc-enhanced-select',
					'options'   => array(
						'text'     => __( 'Text', 'aj-woocommerce-wishlist' ),
						'email'    => __( 'Email', 'aj-woocommerce-wishlist' ),
						'tel'      => __( 'Phone', 'aj-woocommerce-wishlist' ),
						'url'      => __( 'URL', 'aj-woocommerce-wishlist' ),
						'number'   => __( 'Number', 'aj-woocommerce-wishlist' ),
						'date'     => __( 'Date', 'aj-woocommerce-wishlist' ),
						'textarea' => __( 'Textarea', 'aj-woocommerce-wishlist' ),
						'radio'    => __( 'Radio', 'aj-woocommerce-wishlist' ),
						'checkbox' => __( 'Checkbox', 'aj-woocommerce-wishlist' ),
						'select'   => __( 'Select', 'aj-woocommerce-wishlist' ),
					)
				),
				'options' => array(
					'id'        => 'options',
					'name'      => __( 'Enter options for the field', 'aj-woocommerce-wishlist' ),
					'desc'      => __( 'Enter the options for the field type you\'ve selected. Separate options with pipes (|), and key from value with double colon (::). E.g. key::value|key2::value2', 'aj-woocommerce-wishlist' ),
					'type'      => 'aj-field',
					'aj-type' => 'textarea'
				),
			),
			'onoff_field'      => array(
				'id'      => 'active',
				'type'    => 'onoff',
				'default' => 'no'
			),
			'sortable'         => true,
			'save_button'      => array(
				'id'          => 'save',
				'name'        =>  __( 'Save', 'aj-woocommerce-wishlist' ),
			),
			'delete_button'    => array(
				'id'          => 'delete',
				'name'        =>  __( 'Delete', 'aj-woocommerce-wishlist' ),
			)
		),


		'ask_an_estimate_fields_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_additional_fields_settings'
		),

		'text_section_start' => array(
			'name' => __( 'Text customization', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id'   => 'aj_wcwl_text_section_settings'
		),

		'ask_an_estimate_label' => array(
			'name'      => __( '"Ask for an estimate" button label', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ask_an_estimate_label',
			'desc'      => __( 'This option lets you customize the label of "Ask for an Estimate" button', 'aj-woocommerce-wishlist' ),
			'default'   => __( 'Ask for an estimate', 'aj-woocommerce-wishlist' ),
			'type'      => 'aj-field',
			'aj-type' => 'text',
			'deps'      => array(
				'id' => 'aj_wcwl_show_estimate_button',
				'value' => 'yes'
			)
		),

		'additional_info_textarea_label' => array(
			'name'      => __( '"Additional notes" textarea label', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_additional_info_textarea_label',
			'desc'      => __( 'This option lets you customize the label for the "Additional notes" text area', 'aj-woocommerce-wishlist' ),
			'default'   => __( 'Additional notes', 'aj-woocommerce-wishlist' ),
			'type'      => 'aj-field',
			'aj-type' => 'textarea',
		),

		'text_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_text_section_settings'
		),

		'style_section_start' => array(
			'name' => __( 'Style & Color customization', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id'   => 'aj_wcwl_style_section_settings'
		),

		'use_buttons' => array(
			'name'      => __( 'Style of "Ask for an estimate"', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose if you want to show a textual "Ask for an Estimate" link or a button', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ask_an_estimate_style',
			'options'   => array(
				'link'           => __( 'Textual (anchor)', 'aj-woocommerce-wishlist' ),
				'button_default' => __( 'Button with theme style', 'aj-woocommerce-wishlist' ),
				'button_custom'  => __( 'Button with custom style', 'aj-woocommerce-wishlist' )
			),
			'default'   => 'button_default',
			'type'      => 'aj-field',
			'aj-type' => 'radio'
		),

		'ask_an_estimate_colors' => array(
			'name'         => __( '"Ask for an Estimate" button style', 'aj-woocommerce-wishlist' ),
			'id'           => 'aj_wcwl_color_ask_an_estimate',
			'type'         => 'aj-field',
			'aj-type'    => 'multi-colorpicker',
			'colorpickers' => array(
				array(
					'desc' => __( 'Choose colors for the "Ask for an estimate" button', 'aj-woocommerce-wishlist' ),
					array(
						'name' => __( 'Background', 'aj-woocommerce-wishlist' ),
						'id'   => 'background',
						'default' => '#333333'
					),
					array(
						'name' => __( 'Text', 'aj-woocommerce-wishlist' ),
						'id'   => 'text',
						'default' => '#FFFFFF'
					),
					array(
						'name' => __( 'Border', 'aj-woocommerce-wishlist' ),
						'id'   => 'border',
						'default' => '#333333'
					),
				),
				array(
					'desc' => __( 'Choose colors for the "Ask for an estimate" button on hover state', 'aj-woocommerce-wishlist' ),
					array(
						'name' => __( 'Background Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'background_hover',
						'default' => '#4F4F4F'
					),
					array(
						'name' => __( 'Text Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'text_hover',
						'default' => '#FFFFFF'
					),
					array(
						'name' => __( 'Border Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'border_hover',
						'default' => '#4F4F4F'
					),
				),
			),
			'deps' => array(
				'id' => 'aj_wcwl_ask_an_estimate_style',
				'value' => 'button_custom'
			)
		),

		'rounded_buttons_radius' => array(
			'name'      => __( 'Border radius', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose radius for the "Ask for an Estimate" button', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ask_an_estimate_rounded_corners_radius',
			'default'   => 16,
			'type'      => 'aj-field',
			'aj-type' => 'slider',
			'min'       => 1,
			'max'       => 100,
			'deps' => array(
				'id' => 'aj_wcwl_ask_an_estimate_style',
				'value' => 'button_custom'
			)
		),

		'ask_an_estimate_icon' => array(
			'name'      => __( '"Ask for an estimate" icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Select an icon for the "Ask for an Estimate" button (optional)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ask_an_estimate_icon',
			'default'   => apply_filters( 'aj_wcwl_ask_an_estimate_std_icon', '' ),
			'type'      => 'aj-field',
			'aj-type' => 'select',
			'class'     => 'icon-select',
			'options'   => aj_wcwl_get_plugin_icons(),
			'deps' => array(
				'id' => 'aj_wcwl_ask_an_estimate_style',
				'value' => 'button_custom'
			)
		),

		'ask_an_estimate_custom_icon' => array(
			'name'      => __( '"Ask for an estimate" custom icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Upload an icon you\'d like to use for "Ask for an estimate" button (suggested 32px x 32px)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ask_an_estimate_custom_icon',
			'default'   => '',
			'type'      => 'aj-field',
			'aj-type' => 'upload'
		),

		'style_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_style_section_settings'
		),
	)
) );