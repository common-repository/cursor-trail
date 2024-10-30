<?php
class CursorTrailAdmin{
	// action function for above hook
	public static function init() {
		global $user_level;
		add_action ( 'admin_menu', 'CursorTrailAdmin::menus' );
	}

	public static function menus(){
		$page = add_options_page('Cursor Trail', 'Cursor Trail', 'manage_options', 'cursor-trail', 'CursorTrailAdmin::options');
		add_action('admin_head-'.$page, 'CursorTrailAdmin::options_head');
	}


	public static function options_head(){
		?>
		<style type="text/css">
			.nwl-plugin table { width:100%; }
			.nwl-plugin table .col { width:100px; }
			.nwl-plugin table input.wide { width:100%; padding:2px; }
		</style>
		<?php
	}
	
	public static function options() {
		add_option('cursor_trail_options');
		if( is_admin() && !empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'cursor-trail-save') ){
			//Build the array of options here
			foreach ($_POST as $postKey => $postValue){
				if( substr($postKey, 0, 3) == 'ct_' ){
					//For now, no validation, since this is in admin area.
					if($postValue != ''){
						$ct_data[substr($postKey, 3)] = $postValue;
					}
				}
			}
			update_option('cursor_trail_options', $ct_data);
			?>
			<div class="updated"><p><strong><?php _e('Changes saved.'); ?></strong></p></div>
			<?php
		}else{
			$ct_data = get_option('cursor_trail_options');	
		}
		?>
		<div class="wrap nwl-plugin">
			<h1><?php esc_html_e( 'Cursor Trail', 'cursor-trail' ); ?></h1>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="postbox-container-2" class="postbox-container">
						<form method="post" action="">
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<td scope="row">
										<label><?php _e("Cursor Image", 'cursor-trail'); ?></label>
									</td>
									<td>
										<input type="text" name="ct_pointer" value='<?php echo (!empty($ct_data['pointer'])) ? $ct_data['pointer']:WP_PLUGIN_URL.'/cursor-trail/pointer.png'; ?>' class='wide' />
										<i><?php _e("Add the url for the image you want to use as a cursor trail. Defaults to a mouse pointer if left blank.", 'cursor-trail'); ?></i> 
									</td>
								</tr>
								<tr valign="top">
									<td scope="row">
										<label><?php _e("Cursor Fade Speed", 'cursor-trail'); ?></label>
									</td>
									<td>
										<input type="text" name="ct_speed" value='<?php echo (!empty($ct_data['speed'])) ? $ct_data['speed']:900; ?>' class='wide' />
										<i><?php _e("Speed in milliseconds that each cursor trail will take to fade out.", 'cursor-trail'); ?></i> 
									</td>
								</tr>
								<tr valign="top">
									<td scope="row">
										<label><?php _e("Cursor Image Intervals", 'cursor-trail'); ?></label>
									</td>
									<td>
										<input type="text" name="ct_interval" value='<?php echo (!empty($ct_data['interval'])) ? $ct_data['interval']:0; ?>' class='wide' />
										<i><?php _e("Intervals per mouse move, affecting how far apart each image is from each other. The higher the number, the further apart they'll be. We'd recommend somewhere between 2 and 5.", 'cursor-trail'); ?></i> 
									</td>
								</tr>
								<tr valign="top">
									<td scope="row">
										<label><?php _e("Start Date", 'cursor-trail'); ?></label>
									</td>
									<td>
										<input type="text" name="ct_start" value='<?php echo (!empty($ct_data['start'])) ? $ct_data['start']:''; ?>' class='wide' />
										<i><?php _e("If filled, cursors won't start to trail until this date. Use YYYY-MM-DD format.", 'cursor-trail'); ?></i> 
									</td>
								</tr>
								<tr valign="top">
									<td scope="row">
										<label><?php _e("Cut-Off Date", 'cursor-trail'); ?></label>
									</td>
									<td>
										<input type="text" name="ct_end" value='<?php echo (!empty($ct_data['end'])) ? $ct_data['end']:''; ?>' class='wide' />
										<i><?php _e("If filled, cursors won't trail after this date. Use YYYY-MM-DD format.", 'cursor-trail'); ?></i> 
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr valign="top">
									<td colspan="2">	
										<?php echo wp_nonce_field('cursor-trail-save'); ?>
										<p class="submit">
											<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
										</p>							
									</td>
								</tr>
							</tfoot>
						</table>
						</form>
					</div>
					<div id="postbox-container-1" class="postbox-container">
							<div id="ct-plugin-info" class="postbox ">
								<button type="button" class="handlediv button-link" aria-expanded="true">
									<span class="screen-reader-text"><?php echo sprintf(esc_html__('Toggle panel: %s'), esc_html__('About This Plugin','cursor-trail')); ?></span>
									<span class="toggle-indicator" aria-hidden="true"></span>
								</button>
								<h2 class="hndle ui-sortable-handle">
									<span><?php esc_html_e('About This Plugin','cursor-trail'); ?></span>
								</h2>
								<div class="inside">
									<p>
										<?php echo sprintf(esc_html__('This plugin was developed by %s.', 'cursor-trail'), '<a href="http://msyk.es/?utm_source=cursor-trail&utm_medium=settings&utm_campaign=plugins" target="_blank">Marcus Sykes</a>'); ?>
									</p>
									<p style="color:green; font-weight:bold;">
										<?php
											echo sprintf(esc_html__('Please leave us a %s review on %s to show your support and help us keep making this plugin better!','cursor-trail'),
											'<a href="http://wordpress.org/support/view/plugin-reviews/cursor-trail?filter=5" target="_blank">★★★★★</a>',
											'<a href="https://wordpress.org/plugins/cursor-trail/" target="_blank">WordPress.org</a>'
											);
										?>
									</p>
								</div>
							</div>
							<div id="ct-plugin-support" class="postbox ">
								<button type="button" class="handlediv button-link" aria-expanded="true">
									<span class="screen-reader-text"><?php echo sprintf(esc_html__('Toggle panel: %s'), esc_html__('Need Help?','cursor-trail')); ?></span>
									<span class="toggle-indicator" aria-hidden="true"></span>
								</button>
								<h2 class="hndle ui-sortable-handle">
									<span><?php esc_html_e('Need Help?','cursor-trail'); ?></span>
								</h2>
								<div class="inside">					
									<p>
										<?php echo sprintf(esc_html__('Please visit our %s if you have any questions or suggestions.', 'cursor-trail'), 
												'<a href="http://wordpress.org/support/plugin/cursor-trail/" target="_blank">'.esc_html__('Support Forum','cursor-trail').'</a>'); ?>
									</p>
								</div>
							</div>
					</div>
				</div><!-- #post-body -->
			</div><!-- #poststuff -->
		</div>		
		<?php
	}
}
CursorTrailAdmin::init();