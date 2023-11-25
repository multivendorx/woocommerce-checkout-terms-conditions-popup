<?php
class Woo_Checkout_Terms_Conditions_Popup_Frontend {
    public $settings;
    public $popup_settings;
    public $button_settings;

    public function __construct() {
        global $Woo_Checkout_Terms_Conditions_Popup;
        $this->settings = $Woo_Checkout_Terms_Conditions_Popup->options_general_settings;
        $this->popup_settings = $Woo_Checkout_Terms_Conditions_Popup->options_popup_settings;
        $this->button_settings = $Woo_Checkout_Terms_Conditions_Popup->options_button_settings;

        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
        
        add_action('woocommerce_pay_order_before_submit', array($this, 'add_pop_up'), 30);
        add_action('woocommerce_review_order_before_submit', array($this, 'add_pop_up'), 10);
        add_filter('woocommerce_product_tabs', array($this, 'product_terms_condition_tab'));
    }
    
    function product_terms_condition_tab( $tabs ) {
        global $product;
        $product_terms_conditions = get_post_meta( $product->get_id(), '_terms_and_conditions', true );
        if (!empty($product_terms_conditions)) {
            $tabs['terms_conditions'] = array(
                'title' => __( 'Terms and Conditions', 'woocommerce-checkout-terms-conditions-popup' ),
                'priority' => 50,
                'callback' => array($this, 'product_terms_condition_tab_content'), 
            );
        }
        return $tabs;
     }
      
    function product_terms_condition_tab_content() {
        global $product;
        $product_terms_conditions = get_post_meta( $product->get_id(), '_terms_and_conditions', true );
        if ($product_terms_conditions) {
            echo $product_terms_conditions;
        }
    }
     
    function add_pop_up() {
        global $Woo_Checkout_Terms_Conditions_Popup, $woocommerce, $post ;
        if (wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true )) { 
            $pre_text = isset($this->settings['popup_pre_text']) ? $this->settings['popup_pre_text'] : '';
            if (empty($pre_text)) {
                $pre_text = __("I’ve read and accept the ","woocommerce-checkout-terms-conditions-popup");			
            }
            $link_text = isset($this->settings['popup_link_text']) ? $this->settings['popup_link_text'] : '';
            if (empty($link_text)) {
                $link_text = __('Terms & Conditions','woocommerce-checkout-terms-conditions-popup');				
            }
            $line = $pre_text . "<a class='simple_popup_show' href='Javascript:void(0);' title=''>" . $link_text . "</a>"; 
            $product_policy_link_text = __(' & Product Policy','woocommerce-checkout-terms-conditions-popup');
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                if ($product) {
                    $product_terms_conditions = get_post_meta( $product->get_id(), '_terms_and_conditions', true );
                    if (!empty($product_terms_conditions)) { 
                        $product_policy_line = $line . "<a class='simple_popup_product_show' href='Javascript:void(0);' title=''>" . $product_policy_link_text . "</a>"; 
                        break;
                    } else {
                        $product_policy_line = $line;
                    }
                }
            }
            $pop_up_button_text = __('Agree','woocommerce-checkout-terms-conditions-popup');
            $pop_up_width = "80%";
            $pop_up_height = "400px";
            if (!empty($this->settings['button_text']) && $this->settings['button_text'] != " ") {
                $pop_up_button_text = $this->settings['button_text'];					
            }
            if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_width']) && $this->button_settings['button_width'] != " " && $this->button_settings['button_width'] != "0") {
                $pop_up_width = $this->button_settings['button_width']."px";					
            }
            if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_height']) && $this->button_settings['button_height'] != " " && $this->button_settings['button_height'] != "0") {
                $pop_up_height = $this->button_settings['button_height']."px";				
            }
            $woocommerce_terms_page_id = get_option('woocommerce_terms_page_id');				
            ?>
            
            <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
            <link rel="stylesheet" type="text/css" href="<?php echo $Woo_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/css/popup.css" media="screen" />
            <?php
            if (!empty($this->settings['popup_print_text'])) { 
                $pop_up_print_text = $this->settings['popup_print_text']; 
            }
            if (isset($this->settings['is_popup_js_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['is_popup_js_enable'], 'checkbox') == "Enable") {?>
                <script src="<?php echo $Woo_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/js/jquery-1.11.js"></script>
            <?php }?>
            <script type="text/javascript" src="<?php echo $Woo_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/js/simplepopup.js"></script>

            <script type="text/javascript">				
            jQuery(document).ready(function($) {
                <?php if (!isset($_GET['pay_for_order'])) {?>
                $( window ).bind( 'updated_checkout', function() {
                    $(".form-row").find(".woocommerce-terms-and-conditions-checkbox-text").html("<?php echo $product_policy_line; ?>");	
                     $('.simple_popup_show').click(function() {
                        $('#checkoutpopupform').simplepopup();
                        <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                            $("body").css({"overflow-y":"hidden"});
                            
                        <?php }?> 
                     });
                     $('.simple_popup_product_show').click(function() {
                        $('#checkoutproductpopupform').simplepopup();
                        <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                              $("body").css({"overflow-y":"hidden"});
                                 
                         <?php }?> 
                     });
                    <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                        $(document).on('click','.mypopupbuttonclass,.simplepopupBackground',function() {
                            $("body").css({"overflow-y":""});
                         });
                     <?php }?>						
                    <?php if (isset($this->settings['is_popup_agree_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['is_popup_agree_enable'], 'checkbox') == "Enable") { ?>	
                        $(document).on('click',"#checkoutpopupform_close",function() {
                            $('input#terms').prop('checked', true);
                            history.pushState('data', '', '<?php echo wc_get_checkout_url() ?>');
                        });	
                    <?php }?>	
                });
                <?php } 
                if (isset($_GET['pay_for_order']) && !empty($_GET['pay_for_order'])) { ?>
                    $(".form-row").find(".woocommerce-terms-and-conditions-wrapper > p > label > span:first").html("<?php echo $product_policy_line; ?>");	
                     $('.simple_popup_show').click(function() {
                        $('#checkoutpopupform').simplepopup();
                        <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                            $("body").css({"overflow-y":"hidden"});
                        <?php }?> 
                     });
                     $('.simple_popup_product_show').click(function() {
                        $('#checkoutproductpopupform').simplepopup();
                        <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                              $("body").css({"overflow-y":"hidden"});		
                         <?php }?> 
                     });
                     <?php if (isset($this->settings['popup_page_scoller']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_page_scoller'], 'checkbox') == "Enable") { ?> 
                         $(document).on('click','.mypopupbuttonclass,.simplepopupBackground',function() {
                            $("body").css({"overflow-y":""});
                         });
                     <?php }?>						
                    <?php if (isset($this->settings['is_popup_agree_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['is_popup_agree_enable'], 'checkbox') == "Enable") { ?>	
                        $(document).on('click',"#checkoutpopupform_close",function() {
                            $('input#terms').prop('checked', true);	
                        });	
                    <?php }
                } ?>
                <?php if (isset($this->settings['popup_print_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_print_enable'], 'checkbox') == "Enable") { ?> 
                    $(document).on('click','#woocommerce-term-and-condition-print', function(event) {   					 
                        event.preventDefault();		
                        var divToPrint = $('.modal-body').html();
                        var newWin=window.open('','Print-Window');
                        newWin.document.open();
                        newWin.document.write('<html><body onload="window.print()">'+divToPrint+'</body></html>');
                        newWin.document.close();
                        setTimeout(function(){newWin.close();},10);
                    });
                <?php } ?>
            });
            
            </script>
            <style type="text/css" >
            .mypopupbuttonclass {
                background: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_background_color'])) { echo $this->button_settings['button_background_color'];} else { echo '#fff'; } ?>;
                color: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_text_color'])) { echo $this->button_settings['button_text_color'];} else { echo "#333"; } ?>;
                padding: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_padding'])) { echo $this->button_settings['button_padding']."px";} else { echo "8px 16px"; } ?>;
                width: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_width'])) { echo $this->button_settings['button_width']."px";} else { echo "fit-content"; } ?>;
                height: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_height'])) { echo $this->button_settings['button_height']."px";} else { echo "auto"; } ?>;
                line-height: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_font_size'])) { echo $this->button_settings['button_font_size']."px";} else { echo "20px"; } ?>;
                border-radius: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_border_radius'])) { echo $this->button_settings['button_border_radius']."px";} else { echo "5px"; } ?>;
                border: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_border_size'])) { echo $this->button_settings['button_border_size']."px";} else { echo "1px"; } ?> solid  <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_border_color'])) { echo $this->button_settings['button_border_color'];} else { echo "#333"; } ?>;
                font-size: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_font_size'])) { echo $this->button_settings['button_font_size']."px";} else { echo "18px"; } ?>;	
                margin: 0 10px;
            }
            
            .mypopupbuttonclass:hover {
                background: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_background_color_hover'])) { echo $this->button_settings['button_background_color_hover'];} else { echo '#333'; }   ?>;
                color: <?php if (isset($this->button_settings['is_button']) && woo_checkout_terms_conditions_get_settings_value($this->button_settings['is_button'], 'checkbox') == 'Enable' && !empty($this->button_settings['button_text_color_hover'])) { echo $this->button_settings['button_text_color_hover'];} else { echo "#FFF"; } ?>;							
            }
            .simplepopup {
                <?php if (isset($this->popup_settings['popup_div_width']) && !empty($this->popup_settings['popup_div_width'])) { ?> width: <?php echo $this->popup_settings['popup_div_width'];?>%; <?php }?>
                <?php if (isset($this->popup_settings['popup_div_width']) && !empty($this->popup_settings['popup_div_width'])) { ?> min-width: <?php echo $this->popup_settings['popup_div_width'];?>%; <?php }?>
                <?php if (isset($this->popup_settings['popup_div_height']) && !empty($this->popup_settings['popup_div_height'])) { ?> height: <?php echo $this->popup_settings['popup_div_height'];?>vh; <?php }?>
                <?php if (isset($this->popup_settings['popup_div_height']) && !empty($this->popup_settings['popup_div_height'])) { ?> max-height: <?php echo $this->popup_settings['popup_div_height'];?>vh; <?php }?> 
            }		
            
            </style>
            <div id="checkoutpopupform" class="simplepopup"  >
                <div class="modal-header">
                    <h3><?php if (empty($this->popup_settings['popup_heading_text'])) { echo get_post_field('post_title',$woocommerce_terms_page_id); } else { echo $this->popup_settings['popup_heading_text'];} ?></h3>
                    <hr/>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="span12">
                            <p><?php echo wpautop(get_post_field('post_content',$woocommerce_terms_page_id)); ?></p>
                        </div>
                    </div>
                </div>	
                <div class="modal-footer">	
                    <?php if (isset($this->settings['is_popup_agree_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['is_popup_agree_enable'], 'checkbox') == "Enable") { ?>				
                        <a id="checkoutpopupform_close" class="mypopupbuttonclass simplepopupClose" style="float:left; text-align:center; cursor:pointer"><?php echo $pop_up_button_text; ?></a>				
                    <?php } ?>
                        <a id="close_popup" class="mypopupbuttonclass" style="float:left; text-align:center; cursor:pointer"><?php echo __('Close','woocommerce-checkout-terms-conditions-popup'); ?></a>				
                    <?php 
                    if (isset($this->settings['popup_print_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['popup_print_enable'], 'checkbox') == "Enable") { ?>				
                        <a id="woocommerce-term-and-condition-print" class="mypopupbuttonclass term-and-condition-print" style="float:left; text-align:center; cursor:pointer" >
                        <?php if ($this->settings['popup_print_text'] !='') { echo $this->settings['popup_print_text']; } else {echo __('Print','woocommerce-checkout-terms-conditions-popup');} ?></a>				
                    <?php }?>
                </div>					
            </div>
            <div id="checkoutproductpopupform" class="simplepopup" >
                <div class="modal-header">
                    <h3 class=""><?php echo __('Product Policy','woocommerce-checkout-terms-conditions-popup'); ?></h3>
                    <hr/>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="span12"> <?php
                            foreach ( WC()->cart->get_cart() as $cart_item ) {
                                $product = $cart_item['data'];
                                if ($product) {
                                    $product_terms_conditions = get_post_meta( $product->get_id(), '_terms_and_conditions', true );
                                    if (!empty($product_terms_conditions)) { ?>
                                        <p class="product-dtails"><span><?php echo __('Product Name:','woocommerce-checkout-terms-conditions-popup');?> </span>
                                        <?php echo $product->get_name(); ?></p> 
                                        <p class="terms-dtails"><span><?php echo __('Terms & conditions:','woocommerce-checkout-terms-conditions-popup'); ?> </span>
                                        <?php echo $product_terms_conditions; ?></p> 
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>	
                <div class="modal-footer">	
                    <?php if (isset($this->settings['is_popup_agree_enable']) && woo_checkout_terms_conditions_get_settings_value($this->settings['is_popup_agree_enable'], 'checkbox') == "Enable") { ?>				
                        <a id="checkoutpopupform_close" class="mypopupbuttonclass simplepopupClose" style="float:left; text-align:center; cursor:pointer"><?php echo $pop_up_button_text; ?></a>				
                    <?php } ?>				
                        <a id="close_popup" class="mypopupbuttonclass" style="float:left; text-align:center; cursor:pointer"><?php echo __('Close','woocommerce-checkout-terms-conditions-popup'); ?></a>				
                    <?php 
                    if (isset($this->settings['popup_print_enable']) &&  woo_checkout_terms_conditions_get_settings_value($this->settings['popup_print_enable'], 'checkbox') == "Enable") { ?>				
                        <a id="woocommerce-term-and-condition-print" class="mypopupbuttonclass term-and-condition-print" style="float:left; text-align:center; cursor:pointer" >
                        <?php if ($this->settings['popup_print_text'] !='') { echo $this->settings['popup_print_text']; } else { echo __('Print','woocommerce-checkout-terms-conditions-popup'); } ?></a>				
                    <?php }?>
                </div>		
            </div> 			
            <?php
        }	
    }

    function frontend_scripts() {
        global $Woo_Checkout_Terms_Conditions_Popup;
        $frontend_script_path = $Woo_Checkout_Terms_Conditions_Popup->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace( array( 'http:', 'https:' ), '', $frontend_script_path );
        $pluginURL = str_replace( array( 'http:', 'https:' ), '', $Woo_Checkout_Terms_Conditions_Popup->plugin_url );
        $suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        
        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $Woo_Checkout_Terms_Conditions_Popup;
        $frontend_style_path = $Woo_Checkout_Terms_Conditions_Popup->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace( array( 'http:', 'https:' ), '', $frontend_style_path );
        $suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    }
}
