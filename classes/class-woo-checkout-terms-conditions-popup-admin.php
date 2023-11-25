<?php
class Woo_Checkout_Terms_Conditions_Popup_Admin {
    public $settings;
    public function __construct() {
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
        //Add tab in edit product page
        add_filter( 'woocommerce_product_data_tabs', array(&$this, 'add_terms_and_conditions_tab' ));
        //Adding the custom fields
        add_action( 'woocommerce_product_data_panels', array(&$this, 'add_terms_conditions_product_data_panels' ));
        //Save data 
        add_action( 'woocommerce_process_product_meta', array(&$this, 'save_terms_conditions_tab_data' ));
        
        $this->load_class('settings');
        $this->settings = new Woo_Checkout_Terms_Conditions_Popup_Settings();
    }

    function load_class($class_name = '') {
      global $Woo_Checkout_Terms_Conditions_Popup;
        if ('' != $class_name) {
            require_once ($Woo_Checkout_Terms_Conditions_Popup->plugin_path . '/admin/class-' . esc_attr($Woo_Checkout_Terms_Conditions_Popup->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }// End load_class()
    
    /**
     * Admin Scripts
    */
    public function enqueue_admin_script() {
        global $Woo_Checkout_Terms_Conditions_Popup;
        $screen = get_current_screen();
        if (get_current_screen()->id == 'toplevel_page_terms_conditions_popup') {
            wp_enqueue_style( 'terms-conditions-style', $Woo_Checkout_Terms_Conditions_Popup->plugin_url . 'src/style/main.css' );
            wp_enqueue_script( 'terms-conditions-script', $Woo_Checkout_Terms_Conditions_Popup->plugin_url . 'build/index.js', array( 'wp-element' ), $Woo_Checkout_Terms_Conditions_Popup->version, true );
            wp_localize_script( 'terms-conditions-script', 'termsconditionsappLocalizer', apply_filters('terms_conditions_settings', [
                'apiUrl'				=> home_url( '/wp-json' ),
                'nonce'					=> wp_create_nonce( 'wp_rest' ), 
                'custom_button_name'	=> __('Example', 'woocommerce-checkout-terms-conditions-popup'), 
            ] ) );
        }
    }
    
    function add_terms_and_conditions_tab( $tabs ) {
        $tabs['terms_and_conditions'] = array(
            'label'    => __( 'Terms and Conditions', 'woocommerce-checkout-terms-conditions-popup' ),
            'target'   => 'terms_and_conditions_product_data_panel',
        );
        return $tabs;
    }

    function add_terms_conditions_product_data_panels() {
        echo '<div id="terms_and_conditions_product_data_panel" class="panel woocommerce_options_panel hidden">';
        woocommerce_wp_textarea_input(
            array(
                'id'          => 'terms_and_conditions',
                'value'       => get_post_meta( get_the_ID(), '_terms_and_conditions', true ),
                'label'       => __( 'Terms & Conditions', 'woocommerce-checkout-terms-conditions-popup' ),
                'description' => __( 'Set terms and conditions for this product.', 'woocommerce-checkout-terms-conditions-popup' ),
            )
        );
        echo '</div>';
    }

    function save_terms_conditions_tab_data( $post_id ) {
        $terms_and_conditions = isset($_POST['terms_and_conditions']) ? $_POST['terms_and_conditions'] : '';
        if (!empty($terms_and_conditions)) {
            update_post_meta( $post_id, '_terms_and_conditions', esc_html( $terms_and_conditions ) );
        }
    }
}