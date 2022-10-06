<?php
class MVX_Checkout_Terms_Conditions_Popup_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path;
  
  public $jquery_lib_url;

	public function __construct() {
	  global $MVX_Checkout_Terms_Conditions_Popup;
	  
	  $this->lib_path = $MVX_Checkout_Terms_Conditions_Popup->plugin_path . 'lib/';

    $this->lib_url = $MVX_Checkout_Terms_Conditions_Popup->plugin_url . 'lib/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	 */
	public function load_wp_fields() {
	  global $MVX_Checkout_Terms_Conditions_Popup;
	  if ( ! class_exists( 'MVX_WP_Fields' ) )
	    require_once ($this->php_lib_path . 'class-mvx-wp-fields.php');
	  $MVX_WP_Fields = new MVX_WP_Fields(); 
	  return $MVX_WP_Fields;
	}

	/**
	 * WP ColorPicker library
	 */
	public function load_colorpicker_lib() {
	  global $MVX_Checkout_Terms_Conditions_Popup;
	  wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $MVX_Checkout_Terms_Conditions_Popup->version, true );
    wp_enqueue_style( 'wp-color-picker' );
	}
}
	
	
