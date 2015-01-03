<?php
/*
Plugin Name: P2P UI
Plugin URI: https://github.com/herewithme/p2p_ui
Description: Helps with Posts 2 Posts
Version: 0.0.2
Author: Amaury BALMER
Author URI: https://github.com/herewithme
License: In Progress

Original Author: Adam Wood
Original Author URI: https://github.com/adammichaelwood
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'P2P_UI_VERSION', '0.0.2' );
define( 'P2P_UI_CPT_NAME', 'p2pui_connect_type' );

// Plugin URL and PATH
define( 'P2P_UI_URL', plugin_dir_url( __FILE__ ) );
define( 'P2P_UI_DIR', plugin_dir_path( __FILE__ ) );

// Function for easy load files
function _p2p_ui_load_files( $dir, $files, $prefix = '' ) {
	foreach ( $files as $file ) {
		if ( is_file( $dir . $prefix . $file . ".php" ) ) {
			require_once( $dir . $prefix . $file . ".php" );
		}
	}
}

// Plugin client classes
_p2p_ui_load_files( P2P_UI_DIR . 'classes/', array( 'main', 'plugin' ) );

// Plugin admin classes
if ( is_admin() ) {
	_p2p_ui_load_files( P2P_UI_DIR . 'classes/admin/', array( 'metabox' ) );
}

// Plugin activate/deactivate hooks
//register_activation_hook(__FILE__, array('MPT_Plugin', 'activate'));
//register_deactivation_hook(__FILE__, array('MPT_Plugin', 'deactivate'));

add_action( 'plugins_loaded', 'init_p2p_ui_plugin' );
function init_p2p_ui_plugin() {
	// Load translations
	load_plugin_textdomain( 'p2p_ui', false, basename( P2P_UI_DIR ) . '/languages' );

	// Client
	new P2P_UI_Plugin();
	new P2P_UI_Main();

	// Admin
	if ( is_admin() ) {
		// Class admin
		new P2P_UI_Admin_Metabox();
	}
}