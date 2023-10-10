<?php
class Woo_Checkout_Terms_Conditions_Popup_Settings {
    /**
     * Start up
     */
    public function __construct() {
        // Admin menu
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) ); 
    }
    
    public function add_settings_page() {
        add_menu_page( 
            __( 'Terms & Conditions', 'woocommerce-checkout-terms-conditions-popup' ), 
            __( 'Terms & Conditions', 'woocommerce-checkout-terms-conditions-popup' ), 
            'manage_woocommerce', 
            'terms_conditions_popup', 
            [ $this, 'terms_conditions_popup_callback' ],  
            'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 66 66" width="256" height="256"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M20.7 6.4v10.7c0 1.9-1.5 3.4-3.4 3.4H6.1" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M6.1 59.6h-.7c-1.9 0-3.4-1.5-3.4-3.4V5.7c0-1.9 1.5-3.4 3.4-3.4h36.8c1.9 0 3.4 1.5 3.4 3.4v.7" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M49.7 60.3c0 1.9-1.5 3.4-3.4 3.4H9.5c-1.9 0-3.4-1.5-3.4-3.4V21c0-.9.4-1.8 1-2.4L18.3 7.4c.6-.6 1.5-1 2.4-1h25.6c1.9 0 3.4 1.5 3.4 3.4v50.5zM44.7 22.6H18.1M13.4 22.6h-2.3M44.7 28.2H18.1M13.4 28.2h-2.3M44.7 33.9H18.1M13.4 33.9h-2.3M44.7 39.5H18.1M13.4 39.5h-2.3M44.7 45.1H18.1M13.4 45.1h-2.3M44.7 57.7H24.8M39.6 52.5H24.8" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M20 50.8v6.9c0 .5-.4 1-1 1h-6.9c-.5 0-1-.4-1-1v-6.9c0-.5.4-1 1-1H19c.5.1 1 .5 1 1z" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="m13.3 54.7 1.2 1.2 3.2-3.2M61.3 15.7v17h-7.9v-17c0-1.1.9-1.9 1.9-1.9h4.1c1 0 1.9.8 1.9 1.9zm-2.2-1.9v-2.2c0-1-.8-1.8-1.8-1.8h0c-1 0-1.8.8-1.8 1.8v2.2h3.6z" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="m54.11 54.921.061-22.3 6.2.018-.062 22.3z" class="colorStroke000 svgStroke"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="m60.3 54.9-3.1 5.5-3.1-5.5zM61.3 15.7h1.4c.7 0 1.3.6 1.3 1.3v14.2" class="colorStroke000 svgStroke"></path><path d="M27.9 11.7h-2.5v-.9h6v.9h-2.5V19h-1.1v-7.3h.1zm9.5 7.3c-.2-.2-.4-.5-.8-.8-.7.7-1.5.9-2.4.9-1.6 0-2.5-1.1-2.5-2.3 0-1.1.7-1.9 1.6-2.4-.4-.5-.7-1.1-.7-1.7 0-1 .7-2 2.1-2 1 0 1.8.7 1.8 1.7 0 .9-.5 1.5-1.8 2.2.7.8 1.5 1.7 2 2.3.4-.6.6-1.3.8-2.4h1c-.2 1.3-.6 2.3-1.2 3 .4.5.9.9 1.3 1.4l-1.2.1zm-1.3-1.4c-.5-.6-1.4-1.6-2.3-2.6-.4.3-1 .8-1 1.6 0 .9.7 1.6 1.7 1.6.6.1 1.2-.2 1.6-.6zm-2.5-5c0 .6.3 1 .6 1.5.8-.5 1.3-.9 1.3-1.6 0-.5-.3-1-.9-1-.6-.1-1 .5-1 1.1zm11.7 6.1c-.4.2-1.2.4-2.1.4-2.3 0-4-1.4-4-4.1 0-2.5 1.7-4.3 4.2-4.3 1 0 1.6.2 1.9.4l-.3.8c-.4-.2-1-.3-1.6-.3-1.9 0-3.2 1.2-3.2 3.3 0 2 1.1 3.3 3.1 3.3.6 0 1.3-.1 1.7-.3l.3.8z" fill="#000000" class="color000 svgShape"></path></svg>'), 50 
           );

        add_submenu_page( 
            'terms_conditions_popup', 
            __( 'Settings', 'woocommerce-checkout-terms-conditions-popup' ), 
            __( 'Settings', 'woocommerce-checkout-terms-conditions-popup' ), 
            'manage_woocommerce', 
            'terms_conditions_popup#&tab=settings&subtab=general', 
            '__return_null' 
        );

        remove_submenu_page( 'terms_conditions_popup', 'terms_conditions_popup' );
    }

    public function terms_conditions_popup_callback() {
        echo '<div id="woo-admin-terms-conditions-popup-settings"></div>';
    }

}
