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
 * the label to read "Composition".
 *
 * @since  2.1.0
 * @access public
 * @return void
 */
function mc_register_cap_groups() {

	$group = members_get_cap_group( 'type-' . mc_get_composition_post_type() );

	if ( $group ) {

		$group->label = __( 'Composition', 'music-composition' );
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
	$group = sprintf( 'type-%s', mc_get_composition_post_type() );

	// Project caps.
	$caps['create_compositions']           = __( 'Create Compositions',           'music-composition' );
	$caps['edit_compositionss']            = __( 'Edit Compositions',             'music-composition' );
	$caps['edit_others_compositions']      = __( "Edit Others' Compositions",     'music-composition' );
	$caps['read_private_compositions']     = __( 'Read Private Compositions',     'music-composition' );
	$caps['delete_compositions']           = __( 'Delete Compositions',           'music-composition' );
	$caps['delete_private_compositions']   = __( 'Delete Private Compositions',   'music-composition' );
	$caps['delete_published_compositions'] = __( 'Delete Published Compositions', 'music-composition' );
	$caps['delete_others_compositions']    = __( "Delete Others' Compositions",   'music-composition' );
	$caps['edit_private_compositions']     = __( 'Edit Private Compositions',     'music-composition' );
	$caps['edit_published_compositions']   = __( 'Edit Published Compositions',   'music-composition' );
	$caps['publish_compositions']          = __( 'Publish Compositions',          'music-composition' );

	// Category caps.
	$caps['assign_composition_categories'] = __( 'Assign Composition Categories', 'music-composition' );
	$caps['delete_composition_categories'] = __( 'Delete Composition Categories', 'music-composition' );
	$caps['edit_composition_categories']   = __( 'Edit Composition Categories',   'music-composition' );
	$caps['manage_composition_categories'] = __( 'Manage Composition Categories', 'music-composition' );

	// Tag caps.
	$caps['assign_composition_tags'] = __( 'Assign Composition Tags', 'music-composition' );
	$caps['delete_composition_tags'] = __( 'Delete Composition Tags', 'music-composition' );
	$caps['edit_composition_tags']   = __( 'Edit Composition Tags',   'music-composition' );
	$caps['manage_composition_tags'] = __( 'Manage Composition Tags', 'music-composition' );

	// Register each of the capabilities.
	foreach ( $caps as $name => $label )
		members_register_cap( $name, array( 'label' => $label, 'group' => $group ) );
}
