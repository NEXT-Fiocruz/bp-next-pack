<?php
/*
Plugin Name: NEXT BuddyPress Pack
Plugin URI: {URI where you plan to host your plugin file}
Description: Plugin to add some widgets used in NEXT social networks
Version: 0.3.0
Author: Alberto Souza 
Author URI: albertosouza.net
*/

/**
 * Load BP functions safely
 */
function bp_next_pack_loader() {
  include( dirname(__FILE__) . '/bp-next-pack-widgets.php' );
}
add_action( 'bp_include', 'bp_next_pack_loader' );

?>
