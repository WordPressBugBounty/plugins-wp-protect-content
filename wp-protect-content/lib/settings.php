<div class="wrap">
    <h2>WP Protect Content</h2>
	<div id="wpc-tab-menu"><a id="wpc-general" class="wpc-tab-links active" >General</a> <a  id="wpc-support" class="wpc-tab-links">Support</a> <a  id="wpc-other" class="wpc-tab-links">Our Other Plugins</a></div>
    <form method="post" action="options.php" id="wpc-option-form"> 
      <?php settings_fields('wpc-group'); ?>
        <div class="wpc-setting">
			<!-- General Setting -->	
			<div class="first wpc-tab" id="div-wpc-general">
				<table class="form-table">  
				<tr>
				<td style="vertical-align:top;width:auto"><table>
					<tr valign="top">
						<td><input type="checkbox" value="1" name="wpc_disallow_copy_content" id="wpc_disallow_copy_content" <?php checked(get_option('wpc_disallow_copy_content'),1); ?> /> <label for="wpc_disallow_copy_content">Disallow Copy of Content</label></td>
					</tr>
					<tr valign="top">
						<td><input type="checkbox" value="1" name="wpc_disallow_f12" id="wpc_disallow_f12" <?php checked(get_option('wpc_disallow_f12'),1); ?> /> <label for="wpc_disallow_f12">Disable f12 functional key</label>
						   <p>Alert Text Message For F12<br>
							<input type="text" size="40" name="wpc_disallow_f12_msg" id="wpc_disallow_f12_msg" value="<?php echo get_option('wpc_disallow_f12_msg'); ?>" placeholder="Sorry, F12 key has been disabled." /></p>
						
						</td>
					</tr>
					<tr valign="top">
						<td>
							<input type="checkbox" value="1" name="wpc_disallow_right_click" id="wpc_disallow_right_click" <?php checked(get_option('wpc_disallow_right_click'),1); ?> /> <label for="wpc_disallow_right_click">Disable Mouse Right Click</label>
							<p>Alert Text Message For Right Click<br>
							<input type="text" size="40" name="wpc_right_click_msg" id="wpc_right_click_msg" value="<?php echo get_option('wpc_right_click_msg'); ?>" placeholder="Sorry, right-click has been disabled." /></p>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<input type="checkbox" value="1" name="wpc_disallow_drag" id="wpc_disallow_drag" <?php checked(get_option('wpc_disallow_drag'),1); ?> /> <label for="wpc_disallow_drag">Disable Drag/Drop an option</label><br><em>(Disallow an option to save image from website on desktop via drag/drop)</em>
						</td>
					</tr>										
					<tr valign="top">
						<td><label for="wpc_alert_style">Alert box style</label> <select name="wpc_alert_style" id="wpc_alert_style">
						<option value="0" <?php selected(get_option('wpc_alert_style'),0);?>>Default</option>
						<option value="1" <?php selected(get_option('wpc_alert_style'),1);?>>Custom</option>
						</select>
						</td>
					</tr>
					
					<tr valign="top">
						<td><label for="wpc_hide_alert">Hide alert message popup</label><select name="wpc_hide_alert" id="wpc_hide_alert">
						<option value="0" <?php selected(get_option('wpc_hide_alert'),0);?>>No</option>
						<option value="1" <?php selected(get_option('wpc_hide_alert'),1);?>>Yes</option>
						</select>
						<br><i>(If you select "Yes" then alert message popup will not publish on your website)</i>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<input type="checkbox" value="1" name="wpc_debug_mode" id="wpc_debug_mode" <?php checked(get_option('wpc_debug_mode'),1); ?> /> <label for="wpc_debug_mode">Debug Mode</label><em>(Don't apply above restriction for logged in user</em>
						</td>
					</tr>
					<tr><td><?php @submit_button(); ?></td></tr>
					</table>
					</td>
					<td>
					<h2><a href="https://www.wp-experts.in/products/wp-protect-content-pro" style="font-size:28px;">Download Add-on</a></h2>
					<p>Pro version not only demonstrates the flexibility of <br>free version, but also added some important features</p>
					<p><strong>Add-on Features</strong></p>
					<ol>
						<li>Disable copy content (Ctrl+C)</li>
						<li>Disable F12 functional key(Inspect Element)</li>
						<li>Disable Right Click</li>
						<li>Allow right click for links</li>
						<li>Disable print page (Ctrl+P)</li>
						<li>Disable save page (Ctrl+S)</li>
						<li>Disable view page source (Ctrl+U)</li>
						<li>Disable save image via drag/drop</li>
						<li>Page specific features</li>
						<li>Stylish alert box window</li>
						<li>Faster support</li>
					</ol>
					<p><a href="http://www.wp-experts.in/products/wp-protect-content-pro" style="font-weight:bold;font-size:24px;">Click here</a> to download add-on</p>
					
					<p>OR<br><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Y7A3EEYSJSLRS" style="font-weight:bold;font-size:24px;border-radius:5px;">Click here</a> to purchase a single site licence.</p>
					
					
					</td>
					</tr>
				</table>
			</div>
			<div class="wpc-tab" id="div-wpc-support"> <h2>Support</h2> 
				<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( '../images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
				<p><strong>Plugin Author:</strong><br><a href="https://www.wp-experts.in/contact-us" target="_blank">WP Experts Team</a></p>
				<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
			</div>
			<div class="last wpc-tab" id="div-wpc-other">
				<h2>Other plugins</h2>
				<p>
				  <ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
					<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
					<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
					<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
					<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
					<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
					<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
					<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
					<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
					<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
					<li><a href="https://wordpress.org/plugins/otp-login/" target="_blank">OTP Login</a></li>
					<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
					<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
					<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
					<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
					<li><a href="https://wordpress.org/plugins/optimize-wp-website/" target="_blank">Optimize WP Website</a></li>
					<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
					<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
					<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
				  </ol>
				</p>
			</div>
		</div>
    </form>
</div>
