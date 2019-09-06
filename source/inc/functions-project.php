<?php
/**
 * Plugin functions related to the project post type.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Adds a project to the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function mc_add_sticky_project( $project_id ) {
	$project_id = mc_get_project_id( $project_id );

	if ( ! mc_is_project_sticky( $project_id ) )
		return update_option( 'mc_sticky_projects', array_unique( array_merge( mc_get_sticky_projects(), array( $project_id ) ) ) );

	return false;
}

/**
 * Removes a project from the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function mc_remove_sticky_project( $project_id ) {
	$project_id = mc_get_project_id( $project_id );

	if ( mc_is_project_sticky( $project_id ) ) {
		$stickies = mc_get_sticky_projects();
		$key      = array_search( $project_id, $stickies );

		if ( isset( $stickies[ $key ] ) ) {
			unset( $stickies[ $key ] );
			return update_option( 'mc_sticky_projects', array_unique( $stickies ) );
		}
	}

	return false;
}

/**
 * Returns an array of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function mc_get_sticky_projects() {
	return apply_filters( 'mc_get_sticky_projects', get_option( 'mc_sticky_projects', array() ) );
}
