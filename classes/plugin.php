<?php
class P2P_UI_Plugin {
	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action( 'init', array(__CLASS__, 'register_post_type'), 9 );
	}

	/**
	 * 
	 */
	function register_post_type() {
		register_post_type( P2P_UI_CPT_NAME,
			array(
				'labels'        => array(
					'name'          => __('Connections', 'p2p_ui'),
					'singular_name' => __('Connection', 'p2p_ui')
				),
				'public'        => false,
				'show_in_menu'  => true,
				'show_ui'       => true,
				'has_archive'   => false,
				'menu_position' => 102,
				'supports'      => array( 'title', 'editor', 'custom-fields' )
			)
		);
	}


} 