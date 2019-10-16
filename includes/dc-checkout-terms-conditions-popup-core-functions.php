<?php
if(!function_exists('get_checkout_terms_conditions_popup_settings')) {
  function get_checkout_terms_conditions_popup_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}
if(!function_exists('woocommerce_terms_conditions_alert_notice')) { 
	function woocommerce_terms_conditions_alert_notice() {
	?>
	<div id="message" class="error">
      <p><?php printf( __( '%sWoocommerce Checkout Terms & Conditions popup is inactive.%s The %sWooCommerce plugin%s must be active for the Woocommerce Checkout Terms & Conditions popup to work. Please %sinstall & activate WooCommerce%s', DC_CHECKOUT_TERMS_CONDITIONS_POPUP_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
    </div>
    <?php 	
  }
}
if(!function_exists('wc_tc_popup_uninstall')) {
  function wc_tc_popup_uninstall() {
    $options = array(
                      'section_title',
                      'terms_conditions_popup_is_enable',
                      'terms_conditions_popup_close',
                      'terms_conditions_popup_close_text',
                      'terms_conditions_popup_pre_text',
                      'terms_conditions_popup_link_text',
                      'terms_conditions_popup_js_enable',
                      'terms_conditions_popup_heading',
                      'terms_conditions_popup_agree_enable',
                      'terms_conditions_popup_button_width',
                      'terms_conditions_popup_button_height',
                      'terms_conditions_popup_button_text',
                      'terms_conditions_popup_button_border_color',
                      'terms_conditions_popup_button_background_color',
                      'terms_conditions_popup_button_text_color',
                      'terms_conditions_button_font_size',
                      'terms_conditions_button_padding',
                      'terms_conditions_button_border_size',
                      'terms_conditions_button_border_redius',
                      'terms_conditions_popup_button_background_color_hover',
                      'terms_conditions_popup_button_text_color_hover',
                      'terms_conditions_popup_div_width',
                      'terms_conditions_popup_div_height',
                      'terms_conditions_popup_page_scoller',
                      'section_end'
                    );
    foreach ($options as  $option) {
      delete_option($option);
    }
  }
}

?>
