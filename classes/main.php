<?php

class P2P_UI_Main {
	/**
	 * Run the connections generator after WP loads
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( __CLASS__, 'setup_connections' ) );
	}

	/**
	 * Registers connection-types from each connection-type post
	 *
	 * @return bool
	 */
	public static function setup_connections() {
		// Prevent fatal error if posts to posts has been deactivated
		if ( ! function_exists( 'p2p_register_connection_type' ) ) {
			return false;
		}

		$connections_to_register = get_posts( array(
			'numberposts' => - 1, // Get all of them
			'post_type'   => P2P_UI_CPT_NAME,
		) );

		foreach ( $connections_to_register as $connection ) {
			$name = get_post_meta( $connection->ID, 'p2pui_connection_name', true );
			$from = get_post_meta( $connection->ID, 'p2pui_from_post_type', true );
			$to   = get_post_meta( $connection->ID, 'p2pui_to_post_type', true );

			// Set the data only if postmeta isn't empty
			if ( ! empty( $name ) && ! empty( $from ) && ! empty( $to ) ) {

				$cardinality = get_post_meta( $connection->ID, 'p2pui_cardinality', true );
				// Default to one-to-many
				if ( empty( $cardinality ) ) {
					$cardinality = 'one-to-many';
				}

				p2p_register_connection_type( array(        //function from the Posts-to-Posts plugin that actually registers the connection types
					'name'        => $name,
					'from'        => $from,
					'to'          => $to,
					'admin_box'   => array(
						'show'    => 'any',
						'context' => 'advanced'
					),
					'fields'      => self::get_connection_fields( $connection ),
					//see below
					'cardinality' => $cardinality,
					//The below commented lines are additional parameters I haven't gotten to yet, copied from the P2P documentation.
					//'cardinality' => string How many connection can each post have: 'one-to-many', 'many-to-one' or 'many-to-many'. Default: 'many-to-many'
					//'prevent_duplicates' => bool Whether to disallow duplicate connections between the same two posts. Default: true.
					//'self_connections' => bool Whether to allow a post to connect to itself. Default: false.
					//'sortable' => bool|string Whether to allow connections to be ordered via drag-and-drop. Can be 'from', 'to', 'any' or false. Default: false.
					//'title' => string|array The box's title. Default: 'Connected {$post_type}s'
					//'reciprocal' => bool For indeterminate connections: True means all connections are displayed in a single box. False means 'from' connections are shown in one box and 'to' connections are shown in another box. Default: false.
					//'admin_box' => bool|string|array Whether and where to show the admin connections box.
					//'can_create_post' => bool Whether to allow post creation via the connection box. Default: true.
				) );
			}
		}

		return true;
	}

	/**
	 * sifts through the meta-data of the connection-type post to find the fields of data that should be included for recording connection meta-data
	 * These serve as Connection Attributes. Each attribute is recorded as several separate pieces of meta-data
	 * on the post that creates the Connection-Type.
	 *
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public static function get_connection_fields( WP_Post $post ) {
		// Initialize fields to avoid null if get_post_meta returns empty array
		$fields = array();
		for ( $i = 0; get_post_meta( $post->ID, 'fieldkey' . $i, true ); $i ++ ) {
			${'fieldkey' . $i}            = get_post_meta( $post->ID, 'fieldkey' . $i, true );
			$fields[ ${'fieldkey' . $i} ] = array(
				'title' => get_post_meta( $post->ID, 'fieldtitle' . $i, true ),
				'type'  => get_post_meta( $post->ID, 'fieldtype' . $i, true ),
				//Field type in UI: blank for simple text entry, dropdown, or checkbox
			);
			if ( get_post_meta( $post->ID, 'fieldvaluesource' . $i, true ) == 'taxonomy' ) {    // Allows user to define list for a dropdown to come from a WP taxonomy list (works like a foreign-key ref) or a pre-defined set of values (works like ENUM)
				${taxonomies . $i}                      = get_post_meta( $post->ID, 'fieldvaluetaxonomy' . $i, true );
				${get_terms_args . $i}                  = array(
					'hide_empty' => false,
					'fields'     => 'names',
				);
				$fields[ ${'fieldkey' . $i} ]['values'] = get_terms( ${taxonomies . $i}, ${get_terms_args . $i} ); //get the list of taxonomic terms from the specified taxonomy group
			} elseif ( get_post_meta( $post->ID, 'fieldvalues' . $i, false ) ) {
				$fields[ ${'fieldkey' . $i} ]['values'] = get_post_meta( $post->ID, 'fieldvalues' . $i, false );    //get the pre-determined values. will return an array of all values with the key of fieldvaluesX
				if ( get_post_meta( $post->ID, 'fielddefault' . $i, true ) ) {
					$fields[ ${'fieldkey' . $i} ]['default'] = get_post_meta( $post->ID, 'fielddefault' . $i, true );    //get the default value for the feild, if there is one
				}
			}
		}

		return $fields;
	}


}