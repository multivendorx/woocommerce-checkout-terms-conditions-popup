<?php
/**
 * Plugin Name: Woocommerce Checkout Terms Conditions Popup
 * Plugin URI: https://multivendorx.com/
 * Description: This is a woocommerce plugin which show the terms and conditions in popup in checkout page, Here admin can change the text of Terms and conditions as well as link text. admin can also configure the size of popup and text of popup button. Popup will be fully responsive in any device. 
 * Author: MultiVendorX
 * Author URI: https://multivendorx.com/
 * Version: 1.2.3
 * Requires at least: 4.2
 * Tested up to: 6.0.2
 * WC requires at least: 3.0
 * WC tested up to: 6.9.4
 * Text Domain: woocommerce-checkout-terms-conditions-popup
 * Domain Path: /languages/
 *
 */

if ( ! class_exists( 'WC_Dependencies_terms_conditions' ) ) {
require_once trailingslashit(dirname(__FILE__)).'includes/class-mvx-dependencies-terms-conditions.php';
}
require_once trailingslashit(dirname(__FILE__)).'includes/mvx-checkout-terms-conditions-popup-core-functions.php';
require_once trailingslashit(dirname(__FILE__)).'config.php';
register_uninstall_hook( __FILE__, 'wc_tc_popup_uninstall' );
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('MVX_CHECKOUT_TERMS_CONDITIONS_POPUP_PLUGIN_TOKEN')) exit;
if(!defined('MVX_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN')) exit;

if(! WC_Dependencies_terms_conditions::woocommerce_active_check()) {
  add_action( 'admin_notices', 'woocommerce_terms_conditions_alert_notice' );
}
else {
	/**
	 * Plugin page links
	 */
	function checkout_terms_conditions_popup_plugin_links( $links ) {	
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=conditions_popup_settings_tab' ) . '">' . __( 'Settings', MVX_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
			'<a href="https://multivendorx.com/support-forum/">' . __( 'Support', MVX_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
			
		);	
		return array_merge( $plugin_links, $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'checkout_terms_conditions_popup_plugin_links' );
	if(!class_exists('MVX_Checkout_Terms_Conditions_Popup')) {
		require_once( trailingslashit(dirname(__FILE__)).'classes/class-mvx-checkout-terms-conditions-popup.php' );
		global $MVX_Checkout_Terms_Conditions_Popup;
		$MVX_Checkout_Terms_Conditions_Popup = new MVX_Checkout_Terms_Conditions_Popup( __FILE__ );
		$GLOBALS['MVX_Checkout_Terms_Conditions_Popup'] = $MVX_Checkout_Terms_Conditions_Popup;
	}
} 
