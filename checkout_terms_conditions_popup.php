<?php
/**
 * Plugin Name: Woocommerce Checkout Terms Conditions Popup
 * Plugin URI: https://wc-marketplace.com/
 * Description: This is a woocommerce plugin which show the terms and conditions in popup in checkout page, Here admin can change the text of Terms and conditions as well as link text. admin can also configure the size of popup and text of popup button. Popup will be fully responsive in any device. 
 * Author: WC Marketplace
 * Author URI: https://wc-marketplace.com
 * Version: 1.2.2
 * Requires at least: 4.2
 * Tested up to: 6.0
 * WC requires at least: 3.0
 * WC tested up to: 6.6.1
 * Text Domain: woocommerce-checkout-terms-conditions-popup
 * Domain Path: /languages/
 *
 */

if ( ! class_exists( 'WC_Dependencies_terms_conditions' ) ) {
require_once trailingslashit(dirname(__FILE__)).'includes/class-dc-dependencies-terms-conditions.php';
}
require_once trailingslashit(dirname(__FILE__)).'includes/dc-checkout-terms-conditions-popup-core-functions.php';
require_once trailingslashit(dirname(__FILE__)).'config.php';
register_uninstall_hook( __FILE__, 'wc_tc_popup_uninstall' );
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('DC_CHECKOUT_TERMS_CONDITIONS_POPUP_PLUGIN_TOKEN')) exit;
if(!defined('DC_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN')) exit;

if(! WC_Dependencies_terms_conditions::woocommerce_active_check()) {
  add_action( 'admin_notices', 'woocommerce_terms_conditions_alert_notice' );
}
else {
	/**
	 * Plugin page links
	 */
	function checkout_terms_conditions_popup_plugin_links( $links ) {	
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=conditions_popup_settings_tab' ) . '">' . __( 'Settings', DC_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
			'<a href="https://wc-marketplace.com/support-forum/">' . __( 'Support', DC_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
			
		);	
		return array_merge( $plugin_links, $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'checkout_terms_conditions_popup_plugin_links' );
	if(!class_exists('DC_Checkout_Terms_Conditions_Popup')) {
		require_once( trailingslashit(dirname(__FILE__)).'classes/class-dc-checkout-terms-conditions-popup.php' );
		global $DC_Checkout_Terms_Conditions_Popup;
		$DC_Checkout_Terms_Conditions_Popup = new DC_Checkout_Terms_Conditions_Popup( __FILE__ );
		$GLOBALS['DC_Checkout_Terms_Conditions_Popup'] = $DC_Checkout_Terms_Conditions_Popup;
	}
} 
