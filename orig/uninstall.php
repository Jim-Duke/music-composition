<?php
/**
 * Plugin uninstall file.
 *
 * @package    MusicComposition
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Make sure we're actually uninstalling the plugin.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling the plugin.', 'music-composition' ), '<code>' . __FILE__ . '</code>' ) );

/* === Delete plugin options. === */

// Remove options.
delete_option( 'mc_settings'        );
delete_option( 'mc_sticky_compositions' );

/* === Remove capabilities added by the plugin. === */

// Get the administrator role.
$role = get_role( 'administrator' );

// If the administrator role exists, remove added capabilities for the plugin.
if ( ! is_null( $role ) ) {

	// Taxonomy caps.
	$role->remove_cap( 'manage_composition_categories' );
	$role->remove_cap( 'edit_composition_categories'   );
	$role->remove_cap( 'delete_composition_categories' );
	$role->remove_cap( 'assign_composition_categories' );

	$role->remove_cap( 'manage_composition_tags'       );
	$role->remove_cap( 'edit_composition_tags'         );
	$role->remove_cap( 'delete_composition_tags'       );
	$role->remove_cap( 'assign_composition_tags'       );

	// Post type caps.
	$role->remove_cap( 'create_compositions'           );
	$role->remove_cap( 'edit_compositions'             );
	$role->remove_cap( 'edit_others_compositions'      );
	$role->remove_cap( 'publish_compositions'          );
	$role->remove_cap( 'read_private_compositions'     );
	$role->remove_cap( 'delete_compositions'           );
	$role->remove_cap( 'delete_private_compositions'   );
	$role->remove_cap( 'delete_published_compositions' );
	$role->remove_cap( 'delete_others_compositions'    );
	$role->remove_cap( 'edit_private_compositions'     );
	$role->remove_cap( 'edit_published_compositions'   );
}
