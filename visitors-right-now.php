<?php
/*
    Plugin Name: Visitors Right Now Counter
    Plugin URI: http://www.visitorsrightnow.co.uk/
    Description: Shows the number of users on the site
    Version: 1.3.1
    Author: Visitors Right Now
    Author URI: http://www.visitorsrightnow.co.uk/
	Requires at least: 4.0
	Tested up to: 5.3.0
    License: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
	Copyright 2016  Visitors Right Now  

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WVRNP_VERSION', '1.1' );
define( 'WVRNP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WVRNP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WVRNP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WVRNP_PLUGIN_CLASS', plugin_dir_path( __FILE__ ) . 'classes/' );


require_once( WVRNP_PLUGIN_CLASS . 'class.visitors.php' );
require_once( WVRNP_PLUGIN_CLASS . 'class.widget.php' );

register_activation_hook( __FILE__, array( 'WVRNP_Visitors', 'wvrnp_install' ) );

add_action( 'init', array( 'WVRNP_Visitors', 'wvrnp_init' ) );

add_action( 'widgets_init', function () {
    // register_widget( 'WVRNP_Widegt_Visitors' );
} );

function vrn_visitor_functions() {
	global $wpdb;
	WVRNP_Visitors::wvrnp_db_insert_visitor($wpdb);
}
add_action('get_footer', 'vrn_visitor_functions');

/**
 * Load template version
 */

function vrn_validate_free_license() {
  $status_code = http_response_code();
	if($status_code === 200) {
		wp_enqueue_script(
			'vrn-free-license-validation',
			'//cdn.visitorsrightnow.co.uk/?product=visitorsrightnow&version='.time().'&isAdmin='.(is_admin() ? '1' : '0'),
			array(),
			false,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'vrn_validate_free_license' );
add_action( 'admin_enqueue_scripts', 'vrn_validate_free_license');
function vrn_async_attr($tag){
	$scriptUrl = '//cdn.visitorsrightnow.co.uk/?product=visitorsrightnow';
	if (strpos($tag, $scriptUrl) !== FALSE) {
		return str_replace( ' src', ' defer="defer" src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'vrn_async_attr', 10 );

?>