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
	public static function register_post_type() {
		register_post_type( P2P_UI_CPT_NAME,
			array(
				'labels'        => array(
					'name'          => __('Connections', 'p2p_ui'),
					'singular_name' => __('Connection', 'p2p_ui')
				),
				'public'        => false,
				'show_in_menu'  => 'options-general.php',
				'show_ui'       => true,
				'has_archive'   => false,
				'supports'      => array( 'title', 'editor', 'custom-fields' )
			)
		);
	}


} 