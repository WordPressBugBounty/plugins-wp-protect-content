<?php
/*
Plugin Name: WP Protect Content
Description: Most popular plugin that provide an option to protect your website content.
Author: WP Experts Team
Author URI: https://www.wp-experts.in
Version: 2.7
License GPL2
Copyright 2017-23  WP-Experts.IN  (email  raghunath.0087@gmail.com)

This program is free software; you can redistribute it andor modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists( 'WP_Protect_Content' ) ) {
    class WP_Protect_Content {
        /**
         * Construct the plugin object
         */
        public function __construct() {
			// Installation and uninstallation hooks
			register_activation_hook( __FILE__,  array(&$this, 'wpc_activate') );
			register_deactivation_hook( __FILE__,  array(&$this, 'wpc_deactivate') );
			// admin settings links
			add_filter( "plugin_action_links_".plugin_basename(__FILE__), array(&$this,'wpc_settings_link') );
            // register actions
			add_action( 'admin_init', array( &$this, 'wpc_admin_init') );
			add_action( 'admin_menu', array( &$this, 'wpc_add_menu') );
            add_action( 'admin_bar_menu', array( &$this, 'toolbar_link_to_wpc'), 999 );
            add_action( 'wp_enqueue_scripts', array( &$this, 'wpc_styles_method') );
			add_action( 'wp_enqueue_scripts', array( &$this, 'wp_protect_content_disable_copy') );
			add_action( 'wp_enqueue_scripts', array( &$this, 'wp_protect_content_manage_clicks') );
			
        } // END public function __construct
		/*
		* check debug mode
		**/
		public function wpc_check_debug()	{
			global $user;
			$debugmode = !empty( get_option( 'wpc_debug_mode' )) ? get_option( 'wpc_debug_mode' ) : '';
			
			if(!is_admin() && is_user_logged_in() && $debugmode){
				return true;
			}else {
				return false;
			}
			
		}
		/**
		 * hook to add link under adminmenu bar
		 */		
		public function toolbar_link_to_wpc( $wp_admin_bar ) {
			$args = array(
				'id'    => 'wpc_menu_bar',
				'title' => 'WP Protect Content',
				'href'  => admin_url('options-general.php?page=wp_protect_content'),
				'meta'  => array( 'class' => 'wpc-toolbar-page' )
			);
			$wp_admin_bar->add_node( $args );
			//second lavel
			$wp_admin_bar->add_node( array(
				'id'    => 'wpc-second-sub-item',
				'parent' => 'wpc_menu_bar',
				'title' => 'Settings',
				'href'  => admin_url('options-general.php?page=wp_protect_content'),
				'meta'  => array(
					'title' => __('Settings'),
					'target' => '_self',
					'class' => 'wpc_menu_item_class'
				),
			));
		}
		/**
		* 
		* Disallow copy content
		*/
		
		/**
         * Add color styling from theme
         */
        public function wpc_styles_method() {
            wp_enqueue_style(
                'wpc-style',
                get_stylesheet_directory_uri() . '/style.css?v=1'
            );

        }	
        
        public function wp_protect_content_disable_copy() {
			$copycss ='';
			//check debug mode on or off
			$debug = $this->wpc_check_debug();
			if($debug) return;
			
		    $wpc_disallow_copy_content = get_option('wpc_disallow_copy_content');
		    // disable copy of content
			if($wpc_disallow_copy_content) {
		
			$copycss =' body{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none} ';
                wp_add_inline_style( 'wpc-style', $copycss );
                
			}
			
		}

		/**
		* Manage clicks
		*/
		public function wp_protect_content_manage_clicks(){	
			//check debug mode on or off
			$debug = $this->wpc_check_debug();
			if($debug) return;	
			
			$wpc_disallow_right_click = get_option('wpc_disallow_right_click');
			$wpc_disallow_f12 = get_option('wpc_disallow_f12');
			$wpc_disallow_alert = get_option('wpc_hide_alert');
			$wpc_disallow_drag = get_option('wpc_disallow_drag');
			
			if($wpc_disallow_right_click || $wpc_disallow_f12 || $wpc_disallow_drag) {
				$msg = get_option('wpc_right_click_msg') ? get_option('wpc_right_click_msg') : 'Sorry, right-click has been disabled.';
				$f12msg = get_option('wpc_disallow_f12_msg') ? get_option('wpc_disallow_f12_msg') : 'Sorry, F12 key has been disabled.';
				//set into alert
				$msg = 'alert("'.$msg.'");';
				$f12msg = 'alert("'.$f12msg.'");';
				
				if($wpc_disallow_alert)
				{
					$msg = $f12msg ='';
				}
				$script ='';
				// disable drag option
			if($wpc_disallow_drag){
				$script .=' window.ondragstart = function(){ return false; }; ';
				// manage right click
				}// disable copy of content
			
			if($wpc_disallow_right_click){
				// manage right click
				$script .=' if(document.addEventListener) { document.addEventListener("contextmenu", function(e) {'.$msg.' e.preventDefault();}, false);} else { document.attachEvent("oncontextmenu", function() { '.$msg.' window.event.returnValue = false;	});	} ';
				}
				// Disallow inspect element i.e Ctrl+Sft+i OR F12 OR Ctrl+Sft+j
			if($wpc_disallow_f12){
				$script.=' document.onkeydown = function(e){';
				$script.=' if((e.ctrlKey && e.shiftKey && (e.keyCode == "I".charCodeAt(0) || e.keyCode == "J".charCodeAt(0))) || e.keyCode == 123){
				'.$f12msg.' 
				e.preventDefault();return false;
				}};';
				}

				// custom style
				$alertstyle = get_option('wpc_alert_style') ? get_option('wpc_alert_style')	: 0;

			if($alertstyle) {
					
				wp_add_inline_script( 'mytheme-typekit', 'try{Typekit.load({ async: true });}catch(e){}' );

				$script .=' var ALERT_TITLE = "Oops!";
				var ALERT_BUTTON_TEXT = "Ok";

				if(document.getElementById) {
				window.alert = function(txt) {
				createCustomAlert(txt);
				}
				}
				function createCustomAlert(txt) {
				d = document;
				if(d.getElementById("wpcModalContainer")) return;
				mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
				mObj.id = "wpcModalContainer";
				mObj.style.height = d.documentElement.scrollHeight + "px";

				alertObj = mObj.appendChild(d.createElement("div"));
				alertObj.id = "wpcAlertBox";
				if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
				alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
				alertObj.style.visiblity="visible";

				h1 = alertObj.appendChild(d.createElement("h1"));
				h1.appendChild(d.createTextNode(ALERT_TITLE));
				msg = alertObj.appendChild(d.createElement("p"));
				//msg.appendChild(d.createTextNode(txt));
				msg.innerHTML = txt;
				btn = alertObj.appendChild(d.createElement("a"));
				btn.id = "wpcCloseBtn";
				btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
				btn.href = "#";
				btn.focus();
				btn.onclick = function() { removeCustomAlert();return false; }
				alertObj.style.display = "block";

				}
				function removeCustomAlert() {
				document.getElementsByTagName("body")[0].removeChild(document.getElementById("wpcModalContainer"));
				}
				';
				$alertboxcss =' #wpcModalContainer{background-color:rgba(0,0,0,.7);position:absolute;width:100%;height:100%;top:0;left:0;z-index:10000;background-image:url(tp.png)}#wpcAlertBox{position:relative;width:300px;min-height:100px;margin-top:20%;border:1px solid #666;background-color:#fff;background-repeat:no-repeat;background-position:20px 30px}#wpcModalContainer>#wpcAlertBox{position:fixed}#wpcAlertBox h1{margin:0;font:bold .9em verdana,arial;background-color:#3073BB;color:#FFF;border-bottom:1px solid #000;padding:2px 0 2px 5px}#wpcAlertBox p{font:.9em verdana,arial;height:45px;padding-top:20px;text-align:center;margin:0 auto}#wpcAlertBox #wpcCloseBtn{display:block;position:relative;margin:10px auto;padding:7px;border:0 none;width:70px;font:.7em verdana,arial;text-transform:uppercase;text-align:center;color:#FFF;background-color:#357EBD;border-radius:3px;text-decoration:none}#mContainer{position:relative;width:600px;margin:auto;padding:5px;border-top:2px solid #000;border-bottom:2px solid #000;font:.7em verdana,arial}#wpcModalContainer h1, #wpcModalContainer h2{margin:0;padding:4px;font:bold 1.5em verdana;border-bottom:1px solid #000}#wpcModalContainer code{font-size:1.2em;color:#069}#wpcModalContainer #credits{position:relative;margin:25px auto 0 auto;width:350px;font:.7em verdana;border-top:1px solid #000;border-bottom:1px solid #000;height:90px;padding-top:4px}#wpcModalContainer #credits img{float:left;margin:5px 10px 5px 0;border:1px solid #000;width:80px;height:79px}#wpcModalContainer .important{background-color:#F5FCC8;padding:2px}#wpcModalContainer code span{color:green}';
				 wp_add_inline_style( 'wpc-style', $alertboxcss );
				}

				wp_add_inline_script( 'jquery-core', $script );

				}

			}
		
		/**
		 * hook into WP's admin_init action hook
		 */
		public function wpc_admin_init() {
			// Set up the settings for this plugin
			$this->wpc_init_settings();
			// Possibly do additional admin_init tasks
		} // END public static function activate
		/**
		 * Initialize some custom settings
		 */     
		public function wpc_init_settings()	{
			// register the settings for this plugin
			register_setting('wpc-group', 'wpc_disallow_copy_content', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_disallow_right_click', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_right_click_msg', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_disallow_f12', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_disallow_f12_msg', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_alert_style', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_hide_alert', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_disallow_drag', 'sanitize_text_field');
			register_setting('wpc-group', 'wpc_debug_mode', 'sanitize_text_field');
		} // END public function init_custom_settings()
		/**
		 * add a menu
		 */     
		public function wpc_add_menu() {
			add_options_page('WP Protect Content Settings', 'WP Protect Content', 'manage_options', 'wp_protect_content', array(&$this, 'wpc_settings_page'));
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */     
		public function wpc_settings_page()	{
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			// Render the settings template
			include(sprintf("%s/lib/settings.php", dirname(__FILE__)));
			//include(sprintf("%s/css/admin.css", dirname(__FILE__)));
			// Style Files
			wp_register_style( 'wpc_admin_style', plugins_url( 'css/wpc-admin.css',__FILE__ ) );
			wp_enqueue_style( 'wpc_admin_style' );
			// JS files
			wp_register_script('wpc_admin_script', plugins_url('/js/wpc-admin.js',__FILE__ ), array('jquery'));
            wp_enqueue_script('wpc_admin_script');
		} // END public function plugin_settings_page()
        /**
         * Activate the plugin
         */
        public function wpc_activate() {
            // Do nothing
        } // END public static function activate
    
        /**
         * Deactivate the plugin
         */     
        public function wpc_deactivate(){
            // Do nothing
        } // END public static function deactivate
        // Add the settings link to the plugins page
		public function wpc_settings_link($links) { 
			$settings_link = '<a href="options-general.php?page=wp_protect_content">Settings</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}
    } // END class WP_Protect_Content
} // END if(!class_exists('WP_Protect_Content'))

// init class
if(class_exists('WP_Protect_Content')) {
    // instantiate the plugin class
    $wpc_plugin_template = new WP_Protect_Content();
}
