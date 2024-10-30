<?php
/*
Plugin Name: Cursor Trail
Plugin URI: http://wordpress.org/extend/plugins/cursor-trail/
Description: Add a cursor trail to your website mouse pointer, with custom pointer image and url.
Tags: cursor, pointer, effects, festive
Version: 1.1 
Author: Pixelite
Author URI: https://pixelite.com/?utm_source=cursor-trail&utm_medium=plugin-header&utm_campaign=plugins
Text Domain: cursor-trail

Copyright (C) 2022 Marcus Sykes

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function add_cursor_trail(){
	$ct_data = get_option('cursor_trail_options', array());
	if( !empty($ct_data['start']) && current_time('timestamp') <= strtotime($ct_data['start']) ){
		return;		
	}
	if( !empty($ct_data['end']) && current_time('timestamp') >= strtotime($ct_data['end']) ){
		return;
	}
	if( empty($ct_data['interval']) ) $ct_data['interval'] = 0; //for previous versions
	?>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			var container = $(document.body);
			var speed = <?php echo $ct_data['speed']; ?>;
			var ct_mousemove_timeout = null;
			var ct_interval = <?php echo $ct_data['interval']; ?>;;
			var ct_interval_current = 0;
			container.mousemove( function(e){
				if( ct_interval_current == ct_interval ){
					$('<img src="<?php echo $ct_data['pointer']; ?>" />').css({ 'position':'absolute', 'top':e.pageY+5, 'left':e.pageX+5}).prependTo(container).fadeOut(speed, function(){ 
						$(this).remove(); 
					});
					ct_interval_current = 0;
				}else{
					ct_interval_current++;
				}
			});
		});
	</script>
	<?php
}
add_action('wp_head','add_cursor_trail',999);

/* Creating the wp_events table to store event data*/
function cursor_trail_activate() {
	$ct_data = array(
		'pointer' => WP_PLUGIN_URL.'/cursor-trail/pointer.png',
		'speed' => 500,
		'interval' => 2
	);
	add_option('cursor_trail_options', $ct_data);
}
register_activation_hook( __FILE__,'cursor_trail_activate');

if( is_admin() ){
	include('cursor-trail-admin.php');
}