<?php

class P2P_UI_Admin_Metabox {
	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action( 'save_post', array(__CLASS__, 'save_connection_meta'), 10, 2 );
		add_action( 'add_meta_boxes', array(__CLASS__, 'register_metabox') );
	}

	/**
	 *
	 */
	function register_metabox() {
		add_meta_box(
			'p2pui_metabox',
			__('Connection Information', 'p2p_ui'),
			array(__CLASS__, 'render_metabox'),
			P2P_UI_CPT_NAME
		);
	}

	/**
	 * @param WP_Post $post
	 */
	function render_metabox( WP_Post $post ) {
		wp_nonce_field( plugins_url( __FILE__ ), 'p2pui_connect_type_nonce' );

		// Get all registered post types
		$post_types   = get_post_types( array(), 'objects' );

		// Get P2P cardinality modes
		$p2pui_cardinality = array(
			'one-to-many'  => __('One to many', 'p2p_ui'),
			'many-to-one'  => __('Many to one', 'p2p_ui'),
			'many-to-many' => __('Many to many', 'p2p_ui')
		);

		// Get current values for current item
		$current_from_value = get_post_meta( $post->ID, 'p2pui_from_post_type', true );
		$current_to_value = get_post_meta( $post->ID, 'p2pui_to_post_type', true );
		$current_connection_name = get_post_meta( $post->ID, 'p2pui_connection_name', true );
		$current_cardinality = get_post_meta( $post->ID, 'p2pui_cardinality', true );

		include( P2P_UI_DIR . 'views/admin/metabox.php' );
	}

	/**
	 * @param $post_id
	 * @param WP_Post $post
	 *
	 * @return bool
	 */
	function save_connection_meta( $post_id, WP_Post $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['p2pui_connect_type_nonce'] ) ) {
			return false;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['p2pui_connect_type_nonce'], plugins_url( __FILE__ ) ) ) {
			return false;
		}

		// Test custom CPT capability
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		// Save data for connections
		update_post_meta( $post_id, 'p2pui_connection_name', $_POST['p2pui_connection_name'] );
		update_post_meta( $post_id, 'p2pui_from_post_type', $_POST['p2pui_from_post_type'] );
		update_post_meta( $post_id, 'p2pui_to_post_type', $_POST['p2pui_to_post_type'] );
		update_post_meta( $post_id, 'p2pui_cardinality', $_POST['p2pui_cardinality'] );

		return true;
	}
}