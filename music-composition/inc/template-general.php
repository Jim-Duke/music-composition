<?php
/**
 * General template tags for theme authors to use in their themes.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Conditional tag to check if viewing any portfolio page.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function mc_is_library() {

	$is_library = mc_is_archive() || mc_is_single_project();

	return apply_filters( 'mc_is_library', $is_library );
}

/**
 * Conditional tag to check if viewing any type of portfolio archive page.
 *
 * @since  2.0.0
 * @access public
 * @return bool
 */
function mc_is_archive() {

	$is_archive = mc_is_project_archive() || mc_is_author() || mc_is_category() || mc_is_tag();

	return apply_filters( 'mc_is_archive', $is_archive );
}

/**
 * Conditional tag to check if viewing a portfolio category archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function mc_is_category( $term = '' ) {

	return apply_filters( 'mc_is_category', is_tax( mc_get_category_taxonomy(), $term ) );
}

/**
 * Conditional tag to check if viewing a portfolio tag archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function mc_is_tag( $term = '' ) {

	return apply_filters( 'mc_is_tag', is_tax( mc_get_tag_taxonomy(), $term ) );
}

/**
 * Conditional tag to check if viewing a project author archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $author
 * @return bool
 */
function mc_is_author( $author = '' ) {

	return apply_filters( 'mc_is_author', is_post_type_archive( mc_get_project_post_type() ) && is_author( $author ) );
}

/**
 * Print the author archive title.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_single_author_title() {
	echo mc_get_single_author_title();
}

/**
 * Retrieve the author archive title.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_get_single_author_title() {

	return apply_filters( 'mc_get_single_author_title', get_the_author_meta( 'display_name', absint( get_query_var( 'author' ) ) ) );
}

/**
 * Returns the author portfolio archive URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $user_id
 * @global object  $wp_rewrite
 * @global object  $authordata
 * @return string
 */
function mc_get_author_url( $user_id = 0 ) {
	global $wp_rewrite, $authordata;

	$url = '';

	// If no user ID, see if there's some author data we can get it from.
	if ( ! $user_id && is_object( $authordata ) )
		$user_id = $authordata->ID;

	// If we have a user ID, build the URL.
	if ( $user_id ) {

		// Get the author's nicename.
		$nicename = get_the_author_meta( 'user_nicename', $user_id );

		// Pretty permalinks.
		if ( $wp_rewrite->using_permalinks() )
			$url = home_url( user_trailingslashit( trailingslashit( mc_get_author_rewrite_slug() ) . $nicename ) );

		// Ugly permalinks.
		else
			$url = add_query_arg( array( 'post_type' => mc_get_project_post_type(), 'author_name' => $nicename ), home_url( '/' ) );
	}

	return apply_filters( 'mc_get_author_url', $url, $user_id );
}
