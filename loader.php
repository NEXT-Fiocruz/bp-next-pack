<?php
/*
Plugin Name: bp-next-pack
Plugin URI: https://github.com/NEXT-Fiocruz/bp-next-pack
Description: Plugin to add some widgets used in NEXT social networks
Version: 0.4.0
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
