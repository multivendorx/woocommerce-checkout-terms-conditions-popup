<?php
if (!function_exists('woocommerce_terms_conditions_alert_notice')) { 
    function woocommerce_terms_conditions_alert_notice() {
    ?>
    <div id="message" class="error">
      <p><?php printf( __( '%sWoocommerce Checkout Terms & Conditions popup is inactive.%s The %sWooCommerce plugin%s must be active for the Woocommerce Checkout Terms & Conditions popup to work. Please %sinstall & activate WooCommerce%s', WOO_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
    </div>
    <?php 	
  }
}

if (!function_exists('get_woo_checkout_terms_conditions_old_plugin_settings')) {
    function get_woo_checkout_terms_conditions_old_plugin_settings($key = '', $default = false) {
        $woo_old_plugin_settings = get_option($key) ? get_option($key) : array();
        if (empty($key)) {
            return $default;
        }
        if (!isset($woo_old_plugin_settings) || empty($woo_old_plugin_settings)) {
            return $default;
        }
        return $woo_old_plugin_settings;
      }
}

if (!function_exists('save_woo_checkout_terms_conditions_settings')) {
      function save_woo_checkout_terms_conditions_settings($key, $option_val) {
        update_option( $key, $option_val );
  }
}

if (!function_exists('woo_checkout_terms_conditions_get_settings_value')) {
    function woo_checkout_terms_conditions_get_settings_value($key = array(), $default = '') {
        if ($default == 'checkbox' && is_array($key) && !empty($key)) {
            return 'Enable';
        }
        return $default;
    }
}

if (!function_exists('woo_checkout_terms_conditions_admin_tabs')) {
    function woo_checkout_terms_conditions_admin_tabs() {
        $terms_conditions_settings_page_endpoint = apply_filters('woo_checkout_terms_conditions_endpoint_fields_before_value', array(
            'general' => array(
                'tablabel'        =>  __('General', 'woocommerce-checkout-terms-conditions-popup'),
                'apiurl'          =>  'save_terms_conditions',
                'description'     =>  __('Configure basic settings. ', 'woocommerce-checkout-terms-conditions-popup'),
                'icon'            =>  'icon-general',
                'submenu'         =>  'settings',
                'modulename'      =>  [
                    [
                        'key'       =>  'woo_checkout_terms_conditions_config_settings',
                        'type'      =>  'blocktext',
                        'label'     =>  __( 'no_label', 'woocommerce-checkout-terms-conditions-popup' ),
                        'blocktext'      => sprintf( __( 'To enable the Terms and Conditions pop-up, choose the page in WooCommerce settings. Configure it <a href="%s">here</a>.', 'woocommerce-checkout-terms-conditions-popup' ), admin_url( 'admin.php?page=wc-settings&tab=advanced' ) ),
                        'database_value' => '',
                    ],
                    [
                        'key'    => 'popup_pre_text',
                        'label'   => __( "'Terms and Conditions' Notice Text", 'woocommerce-checkout-terms-conditions-popup' ),
                        'type'    => 'text',
                        'desc' => __( "This setting allows the admin to display a notice that requests users to review the site's terms and conditions.", 'woocommerce-checkout-terms-conditions-popup' ),
                        'database_value' => '',
                    ],
                    [
                        'key'    => 'popup_link_text',
                        'label'   => __( "Rename 'Terms & Conditions' text", 'woocommerce-checkout-terms-conditions-popup' ),
                        'type'    => 'text',
                        'desc' => __( "By default we display 'Terms & Conditions' text you can modify as per your need from here.", 'woocommerce-checkout-terms-conditions-popup' ),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'separator_content',
                        'type'      => 'section',
                        'label'     => "",
                    ],
                    [
                        'key'    => 'is_popup_agree_enable',
                        'label'   => __( "Display 'Agree' button in popup", 'woocommerce-checkout-terms-conditions-popup' ),
                        'class'     => 'woo-toggle-checkbox',
                        'type'    => 'checkbox',
                        'options' => array(
                            array(
                            'key'=> "is_popup_agree_enable",
                            'label'=>  __("When enabled, users will see the 'Agree' button in the popup window, allowing them to indicate their agreement with the terms or content presented.", 'woocommerce-checkout-terms-conditions-popup' ),
                            'value'=> "is_popup_agree_enable"
                            ),
                        ),
                        'database_value' => array(),
                    ],
                    [
                        'key'       => 'button_text',
                        'type'      => 'text',
                        'label'     => __( "Rename 'Agree' Button Text", 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __("Replace 'Agree' with the text that users can click to accept the site's terms and conditions.", 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'separator_content',
                        'type'      => 'section',
                        'label'     => "",
                    ],
                    [
                        'key'    => 'popup_print_enable',
                        'label'   => __( "Enable Printer", 'woocommerce-checkout-terms-conditions-popup' ),
                        'class'     => 'woo-toggle-checkbox',
                        'type'    => 'checkbox',
                        'options' => array(
                            array(
                            'key'=> "popup_print_enable",
                            'label'=>  __("Enable the Printer to print the terms and conditions page.", 'woocommerce-checkout-terms-conditions-popup' ),
                            'value'=> "popup_print_enable"
                            ),
                        ),
                        'database_value' => array(),
                    ],
                    [
                        'key'       => 'popup_print_text',
                        'type'      => 'text',
                        'label'     => __( "Display 'Print Button' Text", 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __("By default we display 'Print' text you can modify as per your need from here.",'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'separator_content',
                        'type'      => 'section',
                        'label'     => "",
                    ],
                    [
                        'key'    => 'popup_page_scoller',
                        'label'   => __( "Disable Page Scroller", 'woocommerce-checkout-terms-conditions-popup' ),
                        'class'     => 'woo-toggle-checkbox',
                        'type'    => 'checkbox',
                        'options' => array(
                            array(
                            'key'=> "popup_page_scoller",
                            'label'=>  __("Disable page scroller when popup is open.", 'woocommerce-checkout-terms-conditions-popup' ),
                            'value'=> "popup_page_scoller"
                            ),
                        ),
                        'database_value' => array(),
                    ],
                    [
                        'key'    => 'is_popup_js_enable',
                        'label'   => __( "External Js Lib Enable", 'woocommerce-checkout-terms-conditions-popup' ),
                        'class'     => 'woo-toggle-checkbox',
                        'type'    => 'checkbox',
                        'options' => array(
                            array(
                            'key'=> "is_popup_js_enable",
                            'label'=>  __("If you don't have a jquery lib in your theme then you can enable plugin jquery.", 'woocommerce-checkout-terms-conditions-popup' ),
                            'value'=> "is_popup_js_enable"
                            ),
                        ),
                        'database_value' => array(),
                    ],
                ]
            ),
            'popup_customization' => array(
                'tablabel'        =>  __('Popup Customization', 'woocommerce-checkout-terms-conditions-popup'),
                'apiurl'          =>  'save_terms_conditions',
                'description'     =>  __('Configure popup settings. ', 'woocommerce-checkout-terms-conditions-popup'),
                'icon'            =>  'icon-form-customization',
                'submenu'         =>  'settings',
                'modulename'      =>  [
                    [
                        'key'       => 'popup_heading_text',
                        'type'      => 'text',
                        'label'     => __( 'Custom Popup Heading', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __("Customize the popup title; otherwise, it will display 'Terms and Conditions'.",'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'popup_div_width',
                        'type'      => 'number',
                        'label'     => __( 'Enter Popup Width In percent(%)', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Enter Popup Width in percent(%) only numeric value allowed here.', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'popup_div_height',
                        'type'      => 'number',
                        'label'     => __( 'Enter Popup Height In Percent(%)', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Enter Popup Height in vh only numeric value allowed here. This unit is based on the height of the viewport. A value of 1vh is equal to 1% of the viewport height.', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                ]
            ), 
            'button_customization' => array(
                'tablabel'        =>  __('Button Customization', 'woocommerce-checkout-terms-conditions-popup'),
                'apiurl'          =>  'save_terms_conditions',
                'description'     =>  __('Configure button settings in popup window. ', 'woocommerce-checkout-terms-conditions-popup'),
                'icon'            =>  'icon-button-customization',
                'submenu'         =>  'settings',
                'modulename'      =>  [
                    [
                        'key'    => 'is_button',
                        'label'   => __( "Your own button style", 'woocommerce-checkout-terms-conditions-popup' ),
                        'class'     => 'woo-toggle-checkbox',
                        'type'    => 'checkbox',
                        'options' => array(
                        array(
                            'key'=> "is_button",
                            'label'=> __("Enable the custom design for enquiry button.", 'woocommerce-checkout-terms-conditions-popup'),
                            'value'=> "is_button"
                        ),
                        ),
                        'database_value' => array(),
                    ],
                    [
                        'key'       =>  'woo_checkout_terms_conditions_custom_button_settings',
                        'type'      =>  'blocktext',
                        'depend_checkbox'    => 'is_button',
                        'label'     =>  __( 'no_label', 'woocommerce-checkout-terms-conditions-popup' ),
                        'blocktext'      =>  __( "Custom Button Customizer", 'woocommerce-checkout-terms-conditions-popup' ),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'custom_example_button',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'example_button',
                        'desc'      => __('', 'woocommerce-checkout-terms-conditions-popup'),
                        'label'     => __( 'Button Demo', 'woocommerce-checkout-terms-conditions-popup' )
                    ],
                    [
                        'key'       => 'button_width',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( "Button Width", 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_height',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( "Button Height", 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_font_size',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( 'Font Size', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_padding',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( 'Padding', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_border_size',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( 'Border Size', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_border_radius',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'slider',
                        'class'     =>  'woo-setting-slider-class',
                        'label'     => __( 'Border Radius', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('', 'woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_border_color',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'color',
                        'label'     => __( 'Border Color', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc' => __('Choose button border color.','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_background_color',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'color',
                        'label'     => __( 'Background Color', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Choose button background color.','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_text_color',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'color',
                        'label'     => __( 'Text Color', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Choose button text color.','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_background_color_hover',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'color',
                        'label'     => __( 'Background Color On Hover', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Choose button background color hover.','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                    [
                        'key'       => 'button_text_color_hover',
                        'depend_checkbox'    => 'is_button',
                        'type'      => 'color',
                        'label'     => __( 'Text Color On Hover', 'woocommerce-checkout-terms-conditions-popup' ),
                        'desc'      => __('Choose button text color hover.','woocommerce-checkout-terms-conditions-popup'),
                        'database_value' => '',
                    ],
                ]
            ), 
        ));

        if (!empty($terms_conditions_settings_page_endpoint)) {
            foreach ($terms_conditions_settings_page_endpoint as $settings_key => $settings_value) {
            if (isset($settings_value['modulename']) && !empty($settings_value['modulename'])) {
                foreach ($settings_value['modulename'] as $inter_key => $inter_value) {
                    $change_settings_key = str_replace("-", "_", $settings_key);
                    $option_name = 'woo_checkout_terms_conditions_'.$change_settings_key.'_tab_settings';
                    $database_value = get_option($option_name) ? get_option($option_name) : array();
                    if (!empty($database_value)) {
                        if (isset($inter_value['key']) && array_key_exists($inter_value['key'], $database_value)) {
                            if (empty($inter_value['database_value'])) {
                                $terms_conditions_settings_page_endpoint[$settings_key]['modulename'][$inter_key]['database_value'] = $database_value[$inter_value['key']];
                            }
                        }
                    }
                }
            }
            }
        }

        $terms_conditions_backend_tab_list = apply_filters('woo_checkout_terms_conditions_tab_list', array(
            'terms-conditions-settings' => $terms_conditions_settings_page_endpoint,
        ));
        return $terms_conditions_backend_tab_list;
    }
}
