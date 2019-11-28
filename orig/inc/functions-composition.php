<?php
/**
 * Plugin functions related to the composition post type.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Adds a composition to the list of sticky compositions.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $composition_id
 * @return bool
 */
function mc_add_sticky_composition( $composition_id ) {
	$composition_id = mc_get_composition_id( $composition_id );

	if ( ! mc_is_composition_sticky( $composition_id ) )
		return update_option( 'mc_sticky_compositions', array_unique( array_merge( mc_get_sticky_compositions(), array( $composition_id ) ) ) );

	return false;
}

/**
 * Removes a composition from the list of sticky compositions.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $composition_id
 * @return bool
 */
function mc_remove_sticky_composition( $composition_id ) {
	$composition_id = mc_get_composition_id( $composition_id );

	if ( mc_is_composition_sticky( $composition_id ) ) {
		$stickies = mc_get_sticky_compositions();
		$key      = array_search( $composition_id, $stickies );

		if ( isset( $stickies[ $key ] ) ) {
			unset( $stickies[ $key ] );
			return update_option( 'mc_sticky_compositions', array_unique( $stickies ) );
		}
	}

	return false;
}

/**
 * Returns an array of sticky compositions.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function mc_get_sticky_compositions() {
	return apply_filters( 'mc_get_sticky_compositions', get_option( 'mc_sticky_compositions', array() ) );
}
