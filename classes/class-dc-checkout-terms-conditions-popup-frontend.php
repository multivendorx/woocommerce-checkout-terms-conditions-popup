<?php
class DC_Checkout_Terms_Conditions_Popup_Frontend {
	
	public $terms_conditions_popup_is_enable ;
	public $terms_conditions_popup_pre_text ;
	public $terms_conditions_popup_link_text ;
	public $terms_conditions_popup_js_enable ;
	public $terms_conditions_popup_pop_up_width ;
	public $terms_conditions_popup_pop_up_height ;
	public $terms_conditions_popup_heading ;
	public $terms_conditions_popup_agree_enable ;
	public $terms_conditions_popup_button_text ;
	public $terms_conditions_popup_button_border_color ;
	public $terms_conditions_popup_button_background_color ;
	public $terms_conditions_popup_button_text_color ;
	public $terms_conditions_button_border_size ;
	public $terms_conditions_button_border_redius ;
	public $terms_conditions_popup_button_background_color_hover ;
	public $terms_conditions_popup_button_width ;
	public $terms_conditions_popup_button_height ;
	public $terms_conditions_popup_button_text_color_hover ;
	public $terms_conditions_button_font_size;
	public $terms_conditions_button_padding;
	public $terms_conditions_popup_alert_enable;
	public $terms_conditions_popup_alert_msg;
	public $terms_conditions_popup_div_width;
	public $terms_conditions_popup_div_height;
	public $terms_conditions_popup_page_scoller;
	public $terms_conditions_popup_close;
	public $terms_conditions_popup_close_text;

	public function __construct() {
		$this->terms_conditions_popup_is_enable = get_option('terms_conditions_popup_is_enable');
		$this->terms_conditions_popup_pre_text = get_option('terms_conditions_popup_pre_text');
		$this->terms_conditions_popup_link_text = get_option('terms_conditions_popup_link_text');
		$this->terms_conditions_popup_js_enable = get_option('terms_conditions_popup_js_enable');
		$this->terms_conditions_popup_pop_up_width = get_option('terms_conditions_popup_pop_up_width');
		$this->terms_conditions_popup_pop_up_height = get_option('terms_conditions_popup_pop_up_height');
		$this->terms_conditions_popup_heading = get_option('terms_conditions_popup_heading');
		$this->terms_conditions_popup_agree_enable = get_option('terms_conditions_popup_agree_enable');
		$this->terms_conditions_popup_button_text = get_option('terms_conditions_popup_button_text');
		$this->terms_conditions_popup_button_border_color = get_option('terms_conditions_popup_button_border_color');
		$this->terms_conditions_popup_button_background_color = get_option('terms_conditions_popup_button_background_color');
		$this->terms_conditions_popup_button_text_color = get_option('terms_conditions_popup_button_text_color');
		$this->terms_conditions_button_border_size = get_option('terms_conditions_button_border_size');
		$this->terms_conditions_button_border_redius = get_option('terms_conditions_button_border_redius');
		$this->terms_conditions_popup_button_background_color_hover = get_option('terms_conditions_popup_button_background_color_hover');
		$this->terms_conditions_popup_button_width = get_option('terms_conditions_popup_button_width');
		$this->terms_conditions_popup_button_height = get_option('terms_conditions_popup_button_height');
		$this->terms_conditions_popup_button_text_color_hover = get_option('terms_conditions_popup_button_text_color_hover');
		$this->terms_conditions_button_font_size = get_option('terms_conditions_button_font_size');
		$this->terms_conditions_button_padding = get_option('terms_conditions_button_padding');
		$this->terms_conditions_popup_alert_enable = get_option('terms_conditions_popup_alert_enable');
		$this->terms_conditions_popup_alert_msg = get_option('terms_conditions_popup_alert_msg');
		$this->terms_conditions_popup_div_width = get_option('terms_conditions_popup_div_width');
		$this->terms_conditions_popup_div_height = get_option('terms_conditions_popup_div_height');
		$this->terms_conditions_popup_page_scoller = get_option('terms_conditions_popup_page_scoller');
		$this->terms_conditions_popup_close = get_option('terms_conditions_popup_close');
		$this->terms_conditions_popup_close_text = get_option('terms_conditions_popup_close_text');
		if($this->terms_conditions_popup_is_enable == "yes") {
			
			//enqueue scripts
			//add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
			//enqueue styles
			//add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
	
			add_action( 'dc_checkout_terms_conditions_popup_frontend_hook', array(&$this, 'dc_checkout_terms_conditions_popup_frontend_function'), 10, 2 );		
			//add_action( 'woocommerce_checkout_order_review', array($this, 'add_pop_up'), 30);
			add_action( 'woocommerce_pay_order_before_submit', array($this, 'add_pop_up'), 30);
			add_action( 'woocommerce_review_order_before_submit', array($this, 'add_pop_up'), 10, 0 );
			//add_action( 'woocommerce_after_checkout_validation',       array( $this, 'after_checkout_validation' ),10,1 );		
			
		}
	}
	
	function add_pop_up() {
		global $DC_Checkout_Terms_Conditions_Popup, $woocommerce, $post ;
		if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) { 
			$pre_text = $this->terms_conditions_popup_pre_text;
			if($pre_text == "") {
				$pre_text = "I’ve read and accept the";					
			}
			$link_text = $this->terms_conditions_popup_link_text;
			if($link_text == "") {
				$link_text = "Terms & Conditions";					
			}
			$line = $pre_text."  <a class='simple_popup_show' href='Javascript:void(0);' title=''>".$link_text."</a>"; 
			
			$pop_up_button_text = "Agree";
			$pop_up_width = "80%";
			$pop_up_height = "400px";
			
			
			
			if($this->terms_conditions_popup_button_text != "" && $this->terms_conditions_popup_button_text != " ") {
				$pop_up_button_text = $this->terms_conditions_popup_button_text;					
			}
			if($this->terms_conditions_popup_pop_up_width != "" && $this->terms_conditions_popup_pop_up_width != " " && $this->terms_conditions_popup_pop_up_width != "0") {
				$pop_up_width = $this->terms_conditions_popup_pop_up_width."px";					
			}
			if($this->terms_conditions_popup_pop_up_height != "" && $this->terms_conditions_popup_pop_up_height != " " && $this->terms_conditions_popup_pop_up_height != "0") {
				$pop_up_height = $this->terms_conditions_popup_pop_up_height."px";					
			}
			
			
			$woocommerce_terms_page_id = get_option('woocommerce_terms_page_id');				
			?>
			
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
			<link rel="stylesheet" type="text/css" href="<?php echo $DC_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/css/popup.css" media="screen" />
			 
			<?php if(isset($settings['load_js_lib']) && $settings['load_js_lib'] == "Enable") {?>
			<script src="<?php echo $DC_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/js/jquery-1.11.js"></script>
			<?php }?>
			<script type="text/javascript" src="<?php echo $DC_Checkout_Terms_Conditions_Popup->plugin_url;?>assets/frontend/js/simplepopup.js"></script>
			
			<script type="text/javascript" >				
			jQuery(document).ready(function($) {
				<?php if(!isset($_GET['pay_for_order'])) {?>
				$( window ).bind( 'updated_checkout', function() {
					$(".form-row").find(".woocommerce-terms-and-conditions-checkbox-text").html("<?php echo $line; ?>");	
					 $('.simple_popup_show').click(function(){
					 		 $('#checkoutpopupform').simplepopup();
					 		<?php if($this->terms_conditions_popup_page_scoller == "yes") { ?> 
					 		 $("body").css({"overflow-y":"hidden"});
					 			
					 		<?php }?> 
					 });
					 <?php if($this->terms_conditions_popup_page_scoller == "yes") { ?> 
					  $(document).on('click','.mypopupbuttonclass,.simplepopupBackground',function(){
                            $("body").css({"overflow-y":""});
				 		});
				 	<?php }?>						
					<?php if($this->terms_conditions_popup_agree_enable == "yes") { ?>	
					$(document).on('click',"#checkoutpopupform_close",function(){
						$('input#terms').prop('checked', true);
						history.pushState('data', '', '<?php echo wc_get_checkout_url() ?>');
						
					});	
					<?php }?>

					
				});
				<?php } if(isset($_GET['pay_for_order']) && !empty($_GET['pay_for_order'])) {?>
					
					$(".form-row").find(".woocommerce-terms-and-conditions-wrapper > p > label > span:first").html("<?php echo $line; ?>");	
					 $('.simple_popup_show').click(function(){
					 		 $('#checkoutpopupform').simplepopup();
					 		<?php if($this->terms_conditions_popup_page_scoller == "yes") { ?> 
					 		 $("body").css({"overflow-y":"hidden"});
					 			
					 		<?php }?> 
					 });
					 <?php if($this->terms_conditions_popup_page_scoller == "yes") { ?> 
					  $(document).on('click','.mypopupbuttonclass,.simplepopupBackground',function(){
                            $("body").css({"overflow-y":""});
				 		});
				 	<?php }?>						
					<?php if($this->terms_conditions_popup_agree_enable == "yes") { ?>	
					$(document).on('click',"#checkoutpopupform_close",function(){
						$('input#terms').prop('checked', true);
						
					});	
					<?php }}?>
			});
			
			</script>
			<style type="text/css" >
			.mypopupbuttonclass {
				background: <?php if($this->terms_conditions_popup_button_background_color != ''){ echo $this->terms_conditions_popup_button_background_color;} else { echo '#013ADF'; }   ?>;
				color: <?php if($this->terms_conditions_popup_button_text_color != ""){ echo $this->terms_conditions_popup_button_text_color;} else { echo "#333"; } ?>;
				padding: <?php if($this->terms_conditions_button_padding != ""){ echo $this->terms_conditions_button_padding."px";} else { echo "5px"; } ?>;
				width: <?php if($this->terms_conditions_popup_button_width != ""){ echo $this->terms_conditions_popup_button_width."px";} else { echo "65px"; } ?>;
				height: <?php if($this->terms_conditions_popup_button_height != ""){ echo $this->terms_conditions_popup_button_height."px";} else { echo "30px"; } ?>;
				line-height: <?php if($this->terms_conditions_button_font_size != ""){ echo $this->terms_conditions_button_font_size."px";} else { echo "20px"; } ?>;
				border-radius: <?php if($this->terms_conditions_button_border_redius != ""){ echo $this->terms_conditions_button_border_redius."px";} else { echo "5px"; } ?>;
				border: <?php if($this->terms_conditions_button_border_size != ""){ echo $this->terms_conditions_button_border_size."px";} else { echo "1px"; } ?> solid  <?php if($this->terms_conditions_popup_button_border_color != ""){ echo $this->terms_conditions_popup_button_border_color;} else { echo "#333"; } ?>;
				font-size: <?php if($this->terms_conditions_button_font_size != ""){ echo $this->terms_conditions_button_font_size."px";} else { echo "12px"; } ?>;	
                                margin-right: 20px;
			}
			
			.mypopupbuttonclass:hover {
				background: <?php if($this->terms_conditions_popup_button_background_color_hover != ''){ echo $this->terms_conditions_popup_button_background_color_hover;} else { echo '#333'; }   ?>;
				color: <?php if($this->terms_conditions_popup_button_text_color_hover != ""){ echo $this->terms_conditions_popup_button_text_color_hover;} else { echo "#FFF"; } ?>;							
			}
			.simplepopup {
				<?php if(isset($this->terms_conditions_popup_div_width) && $this->terms_conditions_popup_div_width != '' ) { ?> width: <?php echo $this->terms_conditions_popup_div_width;?>%; <?php }?>
			    <?php if(isset($this->terms_conditions_popup_div_width) && $this->terms_conditions_popup_div_width != '' ) { ?> min-width: <?php echo $this->terms_conditions_popup_div_width;?>%; <?php }?>
			    <?php if(isset($this->terms_conditions_popup_div_height) && $this->terms_conditions_popup_div_height != '' ) { ?> height: <?php echo $this->terms_conditions_popup_div_height;?>vh; <?php }?>
			    <?php if(isset($this->terms_conditions_popup_div_height) && $this->terms_conditions_popup_div_height != '' ) { ?> max-height: <?php echo $this->terms_conditions_popup_div_height;?>vh; <?php }?> 
		        }		
			
			</style>
			<?php if($this->terms_conditions_popup_div_width != '') :?>
			<script type="text/javascript">
			/*jQuery(document).ready(function($){
				if(($( window ).width()*90/100) < (<?php echo $this->terms_conditions_popup_div_width; ?>)) {
					$(".simplepopup").css('width',($( window ).width()*90/100));
					$(".simplepopup").css('min-width',($( window ).width()*90/100));
				}
				$( window ).resize(function() {
					if(($( window ).width()*90/100) < (<?php echo $this->terms_conditions_popup_div_width; ?>)) {
						$(".simplepopup").css('width',($( window ).width()*90/100));
						$(".simplepopup").css('min-width',($( window ).width()*90/100));
					}
					else {
						var width_new = '<?php echo $this->terms_conditions_popup_div_width; ?>'+ %;
						$(".simplepopup").css('width', width_new);
						$(".simplepopup").css('min-width', width_new);						
					}
				});
				 
			
			});*/
			
			</script>
			<?php endif; ?>
			
			<div id="checkoutpopupform" class="simplepopup"  >
				<div class="modal-header">
					
					<h3><?php if($this->terms_conditions_popup_heading == ''){ echo get_post_field('post_title',$woocommerce_terms_page_id); } else { echo $this->terms_conditions_popup_heading;} ?></h3>
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
				<?php if($this->terms_conditions_popup_agree_enable == "yes") { ?>				
					<a id="checkoutpopupform_close" class="mypopupbuttonclass simplepopupClose" style="float:left; text-align:center; cursor:pointer"><?php echo $pop_up_button_text; ?></a>				
				<?php }?>
				
				<?php if($this->terms_conditions_popup_close == "yes") { ?>				
					<a id="close_popup" class="mypopupbuttonclass" style="float:left; text-align:center; cursor:pointer"><?php if($this->terms_conditions_popup_close_text !='') { echo $this->terms_conditions_popup_close_text; }else {echo __('Close',$DC_Checkout_Terms_Conditions_Popup->text_domain);} ?></a>				
				<?php }?>
				</div>					
			</div>
			
			
			
			
			<?php
		}
			
	}
	
/*	
	public function after_checkout_validation($posted) {
		global $DC_Checkout_Terms_Conditions_Popup;
		if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) { 
			if($this->terms_conditions_popup_alert_enable == "yes") {
				if($_POST['terms']!="on") {
					echo "<script type='text/javascript'>alert(<?php echo $this->terms_conditions_popup_alert_msg; ?>);<script>";
					return ;					
				}
			}
			
		}
		
		
	}*/

	function frontend_scripts() {
		global $DC_Checkout_Terms_Conditions_Popup;
		$frontend_script_path = $DC_Checkout_Terms_Conditions_Popup->plugin_url . 'assets/frontend/js/';
		$frontend_script_path = str_replace( array( 'http:', 'https:' ), '', $frontend_script_path );
		$pluginURL = str_replace( array( 'http:', 'https:' ), '', $DC_Checkout_Terms_Conditions_Popup->plugin_url );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Enqueue your frontend javascript from here
	}

	function frontend_styles() {
		global $DC_Checkout_Terms_Conditions_Popup;
		$frontend_style_path = $DC_Checkout_Terms_Conditions_Popup->plugin_url . 'assets/frontend/css/';
		$frontend_style_path = str_replace( array( 'http:', 'https:' ), '', $frontend_style_path );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue your frontend stylesheet from here
	}
	
	function dc_dc_checkout_terms_conditions_popup_frontend_function() {
	  
	  
	}

}
