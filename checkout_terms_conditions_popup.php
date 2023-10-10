<?php
/**
 * Plugin Name: Woocommerce Checkout Terms Conditions Popup
 * Plugin URI: https://wordpress.org/plugins/woocommerce-checkout-terms-conditions-popup/
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

if (! class_exists( 'WC_Dependencies_terms_conditions' )) {
    require_once trailingslashit(dirname(__FILE__)).'includes/class-woo-dependencies-terms-conditions.php';
}
require_once trailingslashit(dirname(__FILE__)).'includes/woo-checkout-terms-conditions-popup-core-functions.php';
require_once trailingslashit(dirname(__FILE__)).'config.php';

if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!defined('WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_PLUGIN_TOKEN')) exit;
if (!defined('WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN')) exit;

if (!WC_Dependencies_terms_conditions::woocommerce_active_check()) {
  add_action( 'admin_notices', 'woocommerce_terms_conditions_alert_notice' );
} else {
    /**
     * Plugin page links
     */
    function checkout_terms_conditions_popup_plugin_links( $links ) {	
        $plugin_links = array(
            '<a href="' . admin_url( 'admin.php?page=terms_conditions_popup#&tab=settings&subtab=general' ) . '">' . __( 'Settings', WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
            '<a href="https://wordpress.org/support/plugin/woocommerce-checkout-terms-conditions-popup/">' . __( 'Support', WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ) . '</a>',
            
        );	
        return array_merge( $plugin_links, $links );
    }
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'checkout_terms_conditions_popup_plugin_links' );
    if (!class_exists('Woo_Checkout_Terms_Conditions_Popup')) {
        require_once( trailingslashit(dirname(__FILE__)).'classes/class-woo-checkout-terms-conditions-popup.php' );
        global $Woo_Checkout_Terms_Conditions_Popup;
        $Woo_Checkout_Terms_Conditions_Popup = new Woo_Checkout_Terms_Conditions_Popup( __FILE__ );
        $GLOBALS['Woo_Checkout_Terms_Conditions_Popup'] = $Woo_Checkout_Terms_Conditions_Popup;
    }
} 
