<?php
/**
 * Registers metadata and related functions for the plugin.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register meta on the 'init' hook.
add_action( 'init', 'mc_register_meta' );

/**
 * Registers custom metadata for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function mc_register_meta() {

	register_meta(
		'post',
		'url',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => '__return_false',
			'single'            => true,
			'show_in_rest'      => true
		)
	);

	register_meta(
		'post',
		'client',
		array(
			'sanitize_callback' => 'wp_strip_all_tags',
			'auth_callback'     => '__return_false',
			'single'            => true,
			'show_in_rest'      => true
		)
	);

	register_meta(
		'post',
		'location',
		array(
			'sanitize_callback' => 'wp_strip_all_tags',
			'auth_callback'     => '__return_false',
			'single'            => true,
			'show_in_rest'      => true
		)
	);

	register_meta(
		'post',
		'start_date',
		array(
			'sanitize_callback' => 'wp_strip_all_tags',
			'auth_callback'     => '__return_false',
			'single'            => true,
			'show_in_rest'      => true
		)
	);

	register_meta(
		'post',
		'end_date',
		array(
			'sanitize_callback' => 'wp_strip_all_tags',
			'auth_callback'     => '__return_false',
			'single'            => true,
			'show_in_rest'      => true
		)
	);
}

/**
 * Returns project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function mc_get_project_meta( $post_id, $meta_key ) {

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Adds/updates project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @param  mixed   $meta_value
 * @return bool
 */
function mc_set_project_meta( $post_id, $meta_key, $meta_value ) {

	return update_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * Deletes project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function mc_delete_project_meta( $post_id, $meta_key ) {

	return delete_post_meta( $post_id, $meta_key );
}
