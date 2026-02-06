<?php
/*
Plugin Name: WP Protect Content
Description: Most popular plugin that provide an option to protect your website content.
Author: WP Experts Team
Author URI: https://www.wp-experts.in
Version: 2.8
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Protect_Content' ) ) {

	class WP_Protect_Content {

		public function __construct() {

			register_activation_hook( __FILE__, array( $this, 'wpc_activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'wpc_deactivate' ) );

			add_filter(
				'plugin_action_links_' . plugin_basename( __FILE__ ),
				array( $this, 'wpc_settings_link' )
			);

			add_action( 'admin_init', array( $this, 'wpc_admin_init' ) );
			add_action( 'admin_menu', array( $this, 'wpc_add_menu' ) );
			add_action( 'admin_bar_menu', array( $this, 'toolbar_link_to_wpc' ), 999 );

			add_action( 'wp_enqueue_scripts', array( $this, 'wpc_styles_method' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_protect_content_disable_copy' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_protect_content_manage_clicks' ) );
		}

		/* ------------------ Helpers ------------------ */

		private function is_debug_enabled() {
			return (
				! is_admin()
				&& is_user_logged_in()
				&& get_option( 'wpc_debug_mode' )
			);
		}

		/* ------------------ Frontend ------------------ */

		public function wpc_styles_method() {
			wp_enqueue_style(
				'wpc-style',
				get_stylesheet_directory_uri() . '/style.css',
				array(),
				'1.0'
			);
		}

		public function wp_protect_content_disable_copy() {

			if ( $this->is_debug_enabled() ) {
				return;
			}

			if ( ! get_option( 'wpc_disallow_copy_content' ) ) {
				return;
			}

			$css = 'body{
				-webkit-touch-callout:none;
				-webkit-user-select:none;
				-khtml-user-select:none;
				-moz-user-select:none;
				-ms-user-select:none;
				user-select:none;
			}';

			wp_add_inline_style( 'wpc-style', $css );
		}

		/**
		 * Manage clicks
		 */
		public function wp_protect_content_manage_clicks() {

				   if ( $this->is_debug_enabled() ) {
						return;
					}

			$wpc_disallow_right_click = get_option( 'wpc_disallow_right_click' );
			$wpc_disallow_f12         = get_option( 'wpc_disallow_f12' );
			$wpc_disallow_alert       = get_option( 'wpc_hide_alert' );
			$wpc_disallow_drag        = get_option( 'wpc_disallow_drag' );

			if ( $wpc_disallow_right_click || $wpc_disallow_f12 || $wpc_disallow_drag ) {

				$msg    = get_option( 'wpc_right_click_msg' )
					? get_option( 'wpc_right_click_msg' )
					: 'Sorry, right-click has been disabled.';

				$f12msg = get_option( 'wpc_disallow_f12_msg' )
					? get_option( 'wpc_disallow_f12_msg' )
					: 'Sorry, F12 key has been disabled.';

				// set alert messages
				$msg    = 'alert("' . $msg . '");';
				$f12msg = 'alert("' . $f12msg . '");';

				if ( $wpc_disallow_alert ) {
					$msg = $f12msg = '';
				}

				$script = '';

				// disable drag option
				if ( $wpc_disallow_drag ) {
					$script .= 'window.ondragstart = function(){ return false; };';
				}

				// disable right click
				if ( $wpc_disallow_right_click ) {
					$script .= '
						if ( document.addEventListener ) {
							document.addEventListener("contextmenu", function(e) {
								' . $msg . '
								e.preventDefault();
							}, false);
						} else {
							document.attachEvent("oncontextmenu", function() {
								' . $msg . '
								window.event.returnValue = false;
							});
						}
					';
				}

				// disable inspect (Ctrl+Shift+I / Ctrl+Shift+J / F12)
				if ( $wpc_disallow_f12 ) {
					$script .= '
						document.onkeydown = function(e){
							if (
								(e.ctrlKey && e.shiftKey && 
								(e.keyCode == "I".charCodeAt(0) || e.keyCode == "J".charCodeAt(0))) ||
								e.keyCode == 123
							){
								' . $f12msg . '
								e.preventDefault();
								return false;
							}
						};
					';
				}

				// custom alert style
				$alertstyle = get_option( 'wpc_alert_style' ) ? get_option( 'wpc_alert_style' ) : 0;

				if ( $alertstyle ) {

					wp_add_inline_script(
						'mytheme-typekit',
						'try{Typekit.load({ async: true });}catch(e){}'
					);

					$script .= '
						var ALERT_TITLE = "Oops!";
						var ALERT_BUTTON_TEXT = "Ok";

						if (document.getElementById) {
							window.alert = function(txt) {
								createCustomAlert(txt);
							}
						}

						function createCustomAlert(txt) {
							d = document;

							if (d.getElementById("wpcModalContainer")) return;

							mObj = d.body.appendChild(d.createElement("div"));
							mObj.id = "wpcModalContainer";
							mObj.style.height = d.documentElement.scrollHeight + "px";

							alertObj = mObj.appendChild(d.createElement("div"));
							alertObj.id = "wpcAlertBox";

							if (d.all && !window.opera) {
								alertObj.style.top = document.documentElement.scrollTop + "px";
							}

							alertObj.style.left =
								(d.documentElement.scrollWidth - alertObj.offsetWidth) / 2 + "px";

							alertObj.style.visiblity = "visible";

							h1 = alertObj.appendChild(d.createElement("h1"));
							h1.appendChild(d.createTextNode(ALERT_TITLE));

							msg = alertObj.appendChild(d.createElement("p"));
							msg.innerHTML = txt;

							btn = alertObj.appendChild(d.createElement("a"));
							btn.id = "wpcCloseBtn";
							btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
							btn.href = "#";
							btn.focus();

							btn.onclick = function() {
								removeCustomAlert();
								return false;
							};

							alertObj.style.display = "block";
						}

						function removeCustomAlert() {
							document.body.removeChild(
								document.getElementById("wpcModalContainer")
							);
						}
					';

					$alertboxcss = '
						#wpcModalContainer{
							background-color:rgba(0,0,0,.7);
							position:absolute;
							width:100%;
							height:100%;
							top:0;
							left:0;
							z-index:10000;
						}
						#wpcAlertBox{
							position:relative;
							width:300px;
							min-height:100px;
							margin-top:20%;
							border:1px solid #666;
							background-color:#fff;
						}
						#wpcAlertBox h1{
							margin:0;
							font:bold .9em verdana,arial;
							background-color:#3073BB;
							color:#FFF;
							padding:2px 5px;
						}
						#wpcAlertBox p{
							font:.9em verdana,arial;
							text-align:center;
							padding:20px;
						}
						#wpcCloseBtn{
							display:block;
							margin:10px auto;
							padding:7px;
							width:70px;
							text-align:center;
							background:#357EBD;
							color:#FFF;
							border-radius:3px;
							text-decoration:none;
						}
					';

					wp_add_inline_style( 'wpc-style', $alertboxcss );
				}

				wp_add_inline_script( 'jquery-core', $script );
			}
		}

		/* ------------------ Admin ------------------ */

		public function wpc_admin_init() {
			$this->wpc_init_settings();
		}

		private function wpc_init_settings() {
			$options = array(
				'wpc_disallow_copy_content',
				'wpc_disallow_right_click',
				'wpc_right_click_msg',
				'wpc_disallow_f12',
				'wpc_disallow_f12_msg',
				'wpc_alert_style',
				'wpc_hide_alert',
				'wpc_disallow_drag',
				'wpc_debug_mode',
			);

			foreach ( $options as $option ) {
				register_setting( 'wpc-group', $option, 'sanitize_text_field' );
			}
		}

		public function wpc_add_menu() {
			add_options_page(
				'WP Protect Content Settings',
				'WP Protect Content',
				'manage_options',
				'wp_protect_content',
				array( $this, 'wpc_settings_page' )
			);
		}

		public function wpc_settings_page() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			include dirname( __FILE__ ) . '/lib/settings.php';

			wp_enqueue_style(
				'wpc_admin_style',
				plugins_url( 'css/wpc-admin.css', __FILE__ )
			);

			wp_enqueue_script(
				'wpc_admin_script',
				plugins_url( 'js/wpc-admin.js', __FILE__ ),
				array( 'jquery' ),
				null,
				true
			);
		}

		public function toolbar_link_to_wpc( $wp_admin_bar ) {
			$wp_admin_bar->add_node( array(
				'id'    => 'wpc_menu_bar',
				'title' => 'WP Protect Content',
				'href'  => admin_url( 'options-general.php?page=wp_protect_content' ),
			) );
		}

		/* ------------------ Utilities ------------------ */

		private function custom_alert_script() {
			return '/* Custom alert JS unchanged */';
		}

		private function custom_alert_css() {
			return '/* Custom alert CSS unchanged */';
		}

		public function wpc_settings_link( $links ) {
			array_unshift(
				$links,
				'<a href="options-general.php?page=wp_protect_content">Settings</a>'
			);
			return $links;
		}

		public function wpc_activate() {}
		public function wpc_deactivate() {}
	}
}

new WP_Protect_Content();
