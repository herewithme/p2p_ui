<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<label for="p2pui_connection_name"><?php _e('Connection Name', 'p2p_ui'); ?></label><br/>
<input type="text" id="p2pui_connection_name" name="p2pui_connection_name" value="<?php echo esc_attr($current_connection_name); ?>" />

<br/>

<label for="p2pui_from_post_type"><?php _e('From Post Type', 'p2p_ui'); ?></label><br/>
<select id="p2pui_from_post_type" name="p2pui_from_post_type">
	<?php foreach ( $post_types as $post_type ): ?>
		<option <?php selected($post_type->name, $current_from_value); ?> value="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->labels->name); ?></option>
	<?php endforeach; ?>
</select>

<br/>

<label for="p2pui_to_post_type"><?php _e('To Post Type', 'p2p_ui'); ?></label><br/>
<select id="p2pui_to_post_type" name="p2pui_to_post_type">
	<?php foreach ( $post_types as $post_type ): ?>
		<option <?php selected($post_type->name, $current_to_value); ?> value="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->labels->name); ?></option>
	<?php endforeach; ?>
</select>

<br/>

<label for="p2pui_cardinality"><?php _e('Cardinality', 'p2p_ui'); ?></label><br/>
<select id="p2pui_cardinality" name="p2pui_cardinality">
	<?php foreach ( $p2pui_cardinality as $cardinality_key => $cardinality_value ): ?>
		<option <?php selected($current_cardinality, $cardinality_key); ?> value="<?php echo esc_attr($cardinality_key); ?>"><?php echo esc_html($cardinality_value); ?></option>
	<?php endforeach; ?>
</select>