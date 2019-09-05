<?php
/**
 * Hooks into the Members plugin and registers capabilities.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register cap groups.
add_action( 'members_register_cap_groups', 'mc_register_cap_groups' );

# Register caps.
add_action( 'members_register_caps', 'mc_register_caps' );

/**
 * Overwrites the cap group registered within the Members plugin.  We want
 * the label to read "Portfolio".
 *
 * @since  2.1.0
 * @access public
 * @return void
 */
function ccp_register_cap_groups() {

	$group = members_get_cap_group( 'type-' . mc_get_project_post_type() );

	if ( $group ) {

		$group->label = __( 'Portfolio', 'music-composition' );
	}
}

/**
 * Registers caps with the Members plugin.  This gives pretty labels for each
 * of the capabilities.
 *
 * @since  2.1.0
 * @access public
 * @return void
 */

function mc_register_caps() {

	$caps  = array();
	$group = sprintf( 'type-%s', mc_get_project_post_type() );

	// Project caps.
	$caps['create_portfolio_projects']           = __( 'Create Projects',           'music-composition' );
	$caps['edit_portfolio_projects']             = __( 'Edit Projects',             'music-composition' );
	$caps['edit_others_portfolio_projects']      = __( "Edit Others' Projects",     'music-composition' );
	$caps['read_private_portfolio_projects']     = __( 'Read Private Projects',     'music-composition' );
	$caps['delete_portfolio_projects']           = __( 'Delete Projects',           'music-composition' );
	$caps['delete_private_portfolio_projects']   = __( 'Delete Private Projects',   'music-composition' );
	$caps['delete_published_portfolio_projects'] = __( 'Delete Published Projects', 'music-composition' );
	$caps['delete_others_portfolio_projects']    = __( "Delete Others' Projects",   'music-composition' );
	$caps['edit_private_portfolio_projects']     = __( 'Edit Private Projects',     'music-composition' );
	$caps['edit_published_portfolio_projects']   = __( 'Edit Published Projects',   'music-composition' );
	$caps['publish_portfolio_projects']          = __( 'Publish Projects',          'music-composition' );

	// Category caps.
	$caps['assign_portfolio_categories'] = __( 'Assign Project Categories', 'music-composition' );
	$caps['delete_portfolio_categories'] = __( 'Delete Project Categories', 'music-composition' );
	$caps['edit_portfolio_categories']   = __( 'Edit Project Categories',   'music-composition' );
	$caps['manage_portfolio_categories'] = __( 'Manage Project Categories', 'music-composition' );

	// Tag caps.
	$caps['assign_portfolio_tags'] = __( 'Assign Project Tags', 'music-composition' );
	$caps['delete_portfolio_tags'] = __( 'Delete Project Tags', 'music-composition' );
	$caps['edit_portfolio_tags']   = __( 'Edit Project Tags',   'music-composition' );
	$caps['manage_portfolio_tags'] = __( 'Manage Project Tags', 'music-composition' );

	// Register each of the capabilities.
	foreach ( $caps as $name => $label )
		members_register_cap( $name, array( 'label' => $label, 'group' => $group ) );
}
