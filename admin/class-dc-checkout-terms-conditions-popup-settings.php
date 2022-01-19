<?php
class DC_Checkout_Terms_Conditions_Popup_Settings {
  
  private $tabs = array();
  
  private $options;
  
  /**
   * Start up
   */
  public function __construct() {
    // Admin menu
   
    //add_action( 'admin_init', array( $this, 'settings_page_init' ) );
    add_action( 'admin_enqueue_scripts', array($this,'terms_conditions_add_color_picker') );
    add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_conditions_popup_settings_tab' ),50 );
    add_action( 'woocommerce_settings_tabs_conditions_popup_settings_tab', array($this, 'terms_settings_tab') );
    add_action( 'woocommerce_update_options_conditions_popup_settings_tab', array($this,'update_settings') );
    
    // Settings tabs
    //add_action('settings_page_dc_checkout_terms_conditions_popup_general_tab_init', array(&$this, 'general_tab_init'), 10, 1);
    
  }
  
  
  function terms_conditions_add_color_picker( $hook ) {
	     global $DC_Checkout_Terms_Conditions_Popup;
			if( is_admin() ) {			 
					// Add the color picker css file      
					$DC_Checkout_Terms_Conditions_Popup->library->load_colorpicker_lib();
			}
	}
  
  
  /**
   *Add woocommerce Setting tabs.
   *
   */
  public function add_conditions_popup_settings_tab($settings_tabs) {
  	global $DC_Checkout_Terms_Conditions_Popup;
  	$settings_tabs['conditions_popup_settings_tab'] = __( 'Terms Conditions Popup Settings', 'woocommerce-checkout-terms-conditions-popup' );
  	return $settings_tabs;  	
  }
  
  
  /**
   *
   *
   */
  public function terms_settings_tab() {
  	global $DC_Checkout_Terms_Conditions_Popup;
  	woocommerce_admin_fields( $this->get_settings() );  	
  }
  
  public function get_settings() {
  	global $DC_Checkout_Terms_Conditions_Popup;
  	
  	 $settings = array(  	 	 
		'section_title' => array(
		'name' => __( 'Terms and Conditions popup settings', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'title',
		'desc' => '',
		'id' => 'wc_settings_tab_demo_section_title'
		),
		
		'terms_conditions_popup_is_enable' => array(
		'name' => __( 'Is Enable', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( 'Enable the functionality', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_is_enable'
		),
		'terms_conditions_popup_close' => array(
		'name' => __( 'Is Close Button Enable', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( 'Check this checkbox if you want close button in popup', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_close'
		),
		'terms_conditions_popup_close_text' => array(
		'name' => __( 'Enter Close Button Text', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter Close Button Text which appear in the terms and conditions popup', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_close_text'
		),
		
		'terms_conditions_popup_pre_text' => array(
		'name' => __( 'Enter the text which will be appear in front page', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter your custom text which will be shown in the checkout page.', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_pre_text'
		),
		
		'terms_conditions_popup_link_text' => array(
		'name' => __( 'Link Text', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter your custom Link Text which will be shown in the checkout page.', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_link_text'
		),
		
		'terms_conditions_popup_js_enable' => array(
		'name' => __( 'External Js Lib Enable', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( "If you don't have a jquery lib in your theme then you can enable plugin jquery.", 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_js_enable'
		),
		
		
		
		'terms_conditions_popup_heading' => array(
		'name' => __( 'Popup Custom Heading', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',		
		'desc' => __( 'Popup Title instead of Terms and condition title', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_heading'
		),
		
		'terms_conditions_popup_agree_enable' => array(
		'name' => __( 'Is Agree Button in popup', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( "Is Agree Button in popup.", 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_agree_enable'
		),
		
		
		
		'terms_conditions_popup_button_width' => array(
		'name' => __( 'Enter the button width', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter the button width in px, (just put the numeric value).', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_button_width'
		),
		
		'terms_conditions_popup_button_height' => array(
		'name' => __( 'Enter the button height', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter the button height in px, (just put the numeric value).', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_button_height'
		),
		
		'terms_conditions_popup_button_text' => array(
		'name' => __( 'Enter the button text which will be appear in the popup window', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter the button text which will be appear in the popup window.', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_button_text'
		),
		
		'terms_conditions_popup_button_border_color' => array(
		'name' => __( 'Button Border Color', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'class' => 'color-field',
		'desc' => __( 'Choose button border color', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_button_border_color'
		),
		
		'terms_conditions_popup_button_background_color' => array(
		'name' => __( 'Button Background Color', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'class' => 'color-field',
		'desc' => __( 'Choose button background color', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_button_background_color'
		),		
		
		'terms_conditions_popup_button_text_color' => array(
		'name' => __( 'Button Text Color', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'class' => 'color-field',
		'desc' => __( 'Choose button text color', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_button_text_color'
		),
		
		
		'terms_conditions_button_font_size' => array(
		'name' => __( 'Button Font Size', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',		
		'desc' => __( 'Enter Button Font Size in px please do not enter suffix px just numeric', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_button_font_size'
		),
		'terms_conditions_button_padding' => array(
		'name' => __( 'Button Padding', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',		
		'desc' => __( 'Enter Button Padding in px please do not enter suffix px just numeric', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_button_padding'
		),
		
		'terms_conditions_button_border_size' => array(
		'name' => __( 'Button Border Size', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',		
		'desc' => __( 'Enter Button Border Size in px please do not enter suffix px just numeric', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_button_border_size'
		),
		
		'terms_conditions_button_border_redius' => array(
		'name' => __( 'Button Border Redius', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',		
		'desc' => __( 'Enter Button Border Redius in px please do not enter suffix px just numeric', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_button_border_redius'
		),
		
		'terms_conditions_popup_button_background_color_hover' => array(
		'name' => __( 'Button Background Color Hover', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'class' => 'color-field',
		'desc' => __( 'Choose button background color hover', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_button_background_color_hover'
		),
		
		'terms_conditions_popup_button_text_color_hover' => array(
		'name' => __( 'Button Text Color Hover', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'class' => 'color-field',
		'desc' => __( 'Choose button text color hover', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_button_text_color_hover'
		),
		'terms_conditions_popup_div_width' => array(
		'name' => __( 'Enter Popup Width In percent(%)', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'number',
		'class' => 'text',
		'desc' => __( 'Enter Popup Width in percent(%) only numeric value allowed here.', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_div_width'
		),
		'terms_conditions_popup_div_height' => array(
		'name' => __( 'Enter Popup Height In Percent(%)', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'number',
		'class' => 'text',
		'desc' => __( 'Enter Popup Height in vh only numeric value allowed here. This unit is based on the height of the viewport. A value of 1vh is equal to 1% of the viewport height.', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_div_height'
		),
		'terms_conditions_popup_page_scoller' => array(
		'name' => __( 'Disable Page Scroller', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( 'Disable page scroller when popup is open.', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_page_scoller'
		),

		'terms_conditions_popup_print' => array(
		'name' => __( 'Enable Printer', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'checkbox',
		'desc' => __( 'Enable the Printer to print the terms and conditions page.', 'woocommerce-checkout-terms-conditions-popup' ),
		'id' => 'terms_conditions_popup_print'
		),	
		'terms_conditions_popup_print_text' => array(
		'name' => __( 'Enter Print Button Text', 'woocommerce-checkout-terms-conditions-popup' ),
		'type' => 'text',
		'desc' => __( 'Enter Print Button Text which appear in the terms and conditions popup', 'woocommerce-checkout-terms-conditions-popup' ),
		'desc_tip' => true,
		'id' => 'terms_conditions_popup_print_text'
		),

		'section_end' => array(
		'type' => 'sectionend',
		'id' => 'wc_settings_tab_demo_section_end'
		)
		);
		return apply_filters( 'wc_settings_tab_conditions_popup_settings_tab', $settings );   	
  	
  }
  
 
	public function update_settings() {
	woocommerce_update_options( $this->get_settings() );
	} 
  
  
  
  
  
 
  

  
  
 
  
  
  
  
  
}
