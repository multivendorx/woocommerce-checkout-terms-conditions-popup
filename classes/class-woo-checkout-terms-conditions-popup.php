<?php
class Woo_Checkout_Terms_Conditions_Popup {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $library;
    public $admin;
    public $frontend;
    public $ajax;

    private $file;
    public $options_general_settings ;
    public $options_popup_settings;
    public $options_button_settings;

    public function __construct($file) {
        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_PLUGIN_TOKEN;
        $this->text_domain = WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN;
        $this->version = WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_PLUGIN_VERSION;	
        
        // default general setting
        $this->options_general_settings = get_option('woo_checkout_terms_conditions_general_tab_settings', array());	
        // popup setting
        $this->options_popup_settings = get_option('woo_checkout_terms_conditions_popup_customization_tab_settings', array());
        // button setting
        $this->options_button_settings = get_option('woo_checkout_terms_conditions_button_customization_tab_settings', array());
        add_action('init', array(&$this, 'init'), 0);
    }
    
    /**
     * initilize plugin on WP init
     */
    function init() {
        
        // Init Text Domain
        $this->load_plugin_textdomain();
        
        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new Woo_Checkout_Terms_Conditions_Popup_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new Woo_Checkout_Terms_Conditions_Popup_Frontend();
        }

        if (!get_option('_is_updated_woo_checkout_terms_condition_settings')) {
            $this->woo_checkout_terms_condition_older_settings_migration();
        }

        if (current_user_can('manage_options')) {
            add_action( 'rest_api_init', array( $this, 'terms_conditions_rest_routes_react_module' ) );
        }
    }
    
    /**
   * Load Localisation files.
   *
   * Note: the first-loaded translation file overrides any following ones if the same translation is present
   *
   * @access public
   * @return void
   */
    public function load_plugin_textdomain() {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'woocommerce-checkout-terms-conditions-popup');
        load_textdomain('woocommerce-checkout-terms-conditions-popup', WP_LANG_DIR . '/woocommerce-checkout-terms-conditions-popup/woocommerce-checkout-terms-conditions-popup-' . $locale . '.mo');
        load_plugin_textdomain('woocommerce-checkout-terms-conditions-popup', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }// End load_class()
    
    /** Cache Helpers *********************************************************/

    /**
     * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
     *
     * @access public
     * @return void
     */
    function nocache() {
        if (!defined('DONOTCACHEPAGE'))
            define("DONOTCACHEPAGE", "true");
        // WP Super Cache constant
    }

    public function terms_conditions_rest_routes_react_module() {
        register_rest_route( 'woo_checkout_terms_conditions/v1', '/fetch_admin_tabs', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'woo_checkout_terms_conditions_fetch_admin_tabs' ),
            'permission_callback' => array( $this, 'terms_conditions_permission' ),
        ] );
        register_rest_route( 'woo_checkout_terms_conditions/v1', '/save_terms_conditions', [
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'woo_checkout_terms_conditions_save_terms_conditions' ),
            'permission_callback' => array( $this, 'terms_conditions_permission' ),
        ] );
    }

    public function terms_conditions_permission() {
        return true;
    }
    
    public function woo_checkout_terms_conditions_fetch_admin_tabs() {
        $woo_checkout_terms_conditions_tabs_data = woo_checkout_terms_conditions_admin_tabs() ? woo_checkout_terms_conditions_admin_tabs() : [];
        return rest_ensure_response( $woo_checkout_terms_conditions_tabs_data );
    }

    public function woo_checkout_terms_conditions_save_terms_conditions($request) {
        $all_details = [];
        $modulename = $request->get_param('modulename');
        $modulename = str_replace("-", "_", $modulename);
        $get_managements_data = $request->get_param( 'model' );
        $optionname = 'woo_checkout_terms_conditions_'.$modulename.'_tab_settings';
        update_option($optionname, $get_managements_data);
        do_action('woo_checkout_terms_conditions_settings_after_save', $modulename, $get_managements_data);
        $all_details['error'] = __('Settings Saved', 'woocommerce-checkout-terms-conditions-popup');
        return $all_details;
        die;
    }

    /*
     * This function will migrate older settings
     */
    function woo_checkout_terms_condition_older_settings_migration() {
        if (!get_option('_is_updated_woo_checkout_terms_condition_settings')) {
            $genaral_settings = $popup_customization_settings = $button_customization_settings = [];
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_pre_text')) {
                $genaral_settings['popup_pre_text'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_pre_text');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_link_text')) {
                $genaral_settings['popup_link_text'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_link_text');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_agree_enable') && get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_agree_enable') == 'yes') {
                $genaral_settings['is_popup_agree_enable'] = array('is_popup_agree_enable');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text')) {
                $genaral_settings['button_text'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_page_scoller') && get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_page_scoller') == 'yes') {
                $genaral_settings['popup_page_scoller'] = array('popup_page_scoller');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_print') && get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_print') == 'yes') {
                $genaral_settings['popup_print_enable'] = array('popup_print_enable');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_print_text')) {
                $genaral_settings['popup_print_text'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_print_text');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_js_enable') && get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_js_enable') == 'yes') {
                $genaral_settings['is_popup_js_enable'] = array('is_popup_js_enable');
            }

            if ($genaral_settings) {
                save_woo_checkout_terms_conditions_settings('woo_checkout_terms_conditions_general_tab_settings', $genaral_settings);
            }

            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_heading')) {
                $popup_customization_settings['popup_heading_text'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_heading');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_div_width')) {
                $popup_customization_settings['popup_div_width'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_div_width');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_div_height')) {
                $popup_customization_settings['popup_div_height'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_div_height');
            }

            if ($popup_customization_settings) {
                save_woo_checkout_terms_conditions_settings('woo_checkout_terms_conditions_popup_customization_tab_settings', $popup_customization_settings);
            }

            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_width')) {
                $button_customization_settings['button_width'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_width');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_height')) {
                $button_customization_settings['button_height'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_height');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_background_color')) {
                $button_customization_settings['button_background_color'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_background_color');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_border_color')) {
                $button_customization_settings['button_border_color'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_border_color');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text_color')) {
                $button_customization_settings['button_text_color'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text_color');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_font_size')) {
                $button_customization_settings['button_font_size'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_font_size');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_padding')) {
                $button_customization_settings['button_padding'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_padding');    
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_border_size')) {
                $button_customization_settings['button_border_size'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_border_size');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_border_redius')) {
                $button_customization_settings['button_border_radius'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_button_border_redius');    
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_background_color_hover')) {
                $button_customization_settings['button_background_color_hover'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_background_color_hover');
            }
            if (get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text_color_hover')) {
                $button_customization_settings['button_text_color_hover'] = get_woo_checkout_terms_conditions_old_plugin_settings('terms_conditions_popup_button_text_color_hover');
            }
            if ($button_customization_settings) {
                save_woo_checkout_terms_conditions_settings('woo_checkout_terms_conditions_button_customization_tab_settings', $button_customization_settings);
            }

            update_option('_is_updated_woo_checkout_terms_condition_settings', true);
        }
    }

}
