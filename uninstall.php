<?php 

if( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

global $wpdb;

$table_name = $wpdb->get_blog_prefix() . 'vnr_visitors';

$sql = "DROP TABLE IF EXISTS $table_name;";

$wpdb->query($sql);

// $ch = curl_init();

// curl_setopt_array( $ch, array(

//     CURLOPT_URL => 'http://backlinkstracker.com/',

//     CURLOPT_RETURNTRANSFER => false,

//     CURLOPT_POST => true,

//     CURLOPT_POSTFIELDS => http_build_query( array( 'hcawp_delete_install' => 'true', 'hcawp_domain' => 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], 'hcawp_installed_plugin_id' => 6 ) )

// ));

// $res = curl_exec( $ch );

// curl_close( $ch );