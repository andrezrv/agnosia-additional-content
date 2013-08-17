<?php

/**
 * @package Agnosia_Additional_Content
 * @version 0.1
 */

/*
Plugin Name: Agnosia Additional Content
Plugin URI: http://wordpress.org/extend/plugins/agnosia-bootstrap-carousel/
Description: For Agnosia theme and child themes, allows to add content in posts and pages after the site's header, before the article, after the article and before the site's footer.
Author: Andr&eacute;s Villarreal
Version: 0.1
*/

/* Fire meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'agnosia_ac_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'agnosia_ac_post_meta_boxes_setup' );
add_action( 'agnosia_ac_before_container_html', 'agnosia_ac_get_before_container_html' );
add_action( 'agnosia_ac_before_content_html', 'agnosia_ac_get_before_content_html' );
add_action( 'agnosia_ac_after_container_html', 'agnosia_ac_get_after_container_html' );
add_action( 'agnosia_ac_after_content_html', 'agnosia_ac_get_after_content_html' );

/* Meta box setup function. */
function agnosia_ac_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'agnosia_ac_add_post_meta_boxes' );

	if ( 'agnosia' != wp_get_theme()->Template ) :
		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', 'agnosia_ac_save_post_meta', 10 , 2 );
	endif;

}

/* Create meta boxes to be displayed on the post editor screen. */
function agnosia_ac_add_post_meta_boxes() {

	global $post;

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;

	if ( current_user_can( 'content_enable_additional_html' ) 
		and ( !isset( $meta_value['block_additional_html'] ) 
			or !$meta_value['block_additional_html'] 
		)
	) :

		add_meta_box(
			'post-custom-html',		// Unique ID
			esc_html__( 'Additional HTML', 'agnosia' ),		// Title
			'agnosia_ac_post_additional_html',		// Callback function
			'',					// Admin page (or post type)
			'advanced',					// Context
			'high'					// Priority
		);

	endif;

}

/* Display additional meta box. */
function agnosia_ac_post_additional_html( $post, $box ) { 

	global $post;

	wp_nonce_field( basename( __FILE__ ), 'agnosia_post_additional_html_nonce' );

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;

	$before_content_html = isset( $meta_value['before_content_html'] ) ? $meta_value['before_content_html'] : '' ;
	$after_content_html = isset( $meta_value['after_content_html'] ) ? $meta_value['after_content_html'] : '' ;
	$before_container_html = isset( $meta_value['before_container_html'] ) ? $meta_value['before_container_html'] : '' ;
	$after_container_html = isset( $meta_value['after_container_html'] ) ? $meta_value['after_container_html'] : '' ;

	?>

	<div class="agnosia_additional_html">		

		<p>
			<label for="agnosia_post_meta[before_container_html]"><strong><?php _e( 'HTML before container' , 'agnosia' ); ?>:</strong></label><br />
			<?php wp_editor( $before_container_html , 'agnosia_post_meta[before_container_html]' , array( 'textarea_rows' => 20 ) ); ?>
		</p>

		<p>
			<label for="agnosia_post_meta[before_content_html]"><strong><?php _e( 'HTML before content' , 'agnosia' ); ?>:</strong></label><br />
			<?php wp_editor( $before_content_html , 'agnosia_post_meta[before_content_html]' , array( 'textarea_rows' => 1 ) ); ?>
		</p>

		<p>
			<label for="agnosia_post_meta[after_content_html]"><strong><?php _e( 'HTML after content' , 'agnosia' ); ?>:</strong></label><br />
			<?php wp_editor( $after_content_html , 'agnosia_post_meta[after_content_html]' , array( 'textarea_rows' => 1 ) ); ?>
		</p>

		<p>
			<label for="agnosia_post_meta[after_container_html]"><strong><?php _e( 'HTML after container' , 'agnosia' ); ?>:</strong></label><br />
			<?php wp_editor( $after_container_html , 'agnosia_post_meta[after_container_html]' , array( 'textarea_rows' => 1 ) ); ?>
		</p>

		<p><?php _e( 'The content of these fields, besides shortcodes, will display as HTML, and since they are not previously formatted, we strongly advise not to use this feature unless you know exactly what you\'re doing. Otherwise, you might break your design.' , 'agnosia' ) ; ?></p>

	</div>

	<?php
	
}

function agnosia_ac_get_before_container_html() {

	global $post ;

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;
	$before_container_html = isset( $meta_value['before_container_html'] ) ? $meta_value['before_container_html'] : '' ;
	$before_container_html = do_shortcode( $before_container_html );

	echo $before_container_html;

}

function agnosia_ac_get_before_content_html() {

	global $post ;

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;
	$before_content_html = isset( $meta_value['before_content_html'] ) ? $meta_value['before_content_html'] : '' ;
	$before_content_html = do_shortcode( $before_content_html );

	echo $before_content_html;

}

function agnosia_ac_get_after_container_html() {

	global $post ;

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;
	$after_container_html = isset( $meta_value['after_container_html'] ) ? $meta_value['after_container_html'] : '' ;
	$after_container_html = do_shortcode( $after_container_html );

	echo $after_container_html;

}

function agnosia_ac_get_after_content_html() {

	global $post ;

	$meta_value = get_post_meta( $post->ID, 'agnosia_post_meta' , true ) ;
	$after_content_html = isset( $meta_value['after_content_html'] ) ? $meta_value['after_content_html'] : '' ;
	$after_content_html = do_shortcode( $after_content_html );

	echo $after_content_html;

}

if ( 'agnosia' != wp_get_theme()->Template ) :

	/* Save the meta box's post metadata. */
	function agnosia_ac_save_post_meta( $post_id, $post ) {

		/* Verify the nonces before proceeding. */

		if ( !isset( $_POST['agnosia_post_additional_html_nonce'] ) 
			|| !wp_verify_nonce( $_POST['agnosia_post_additional_html_nonce'] , basename( __FILE__ ) ) 
		) :
		
			return $post_id;
		
		endif;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) :
			return $post_id;
		endif;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['agnosia_post_meta'] ) ? $_POST['agnosia_post_meta'] : '' );

		/* Get the meta key. */
		$meta_key = 'agnosia_post_meta';

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( $new_meta_value == '' && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

	}

endif;

?>