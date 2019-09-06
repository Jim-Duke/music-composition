<?php
/**
 * File for registering custom post types.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register custom post types on the 'init' hook.
add_action( 'init', 'mc_register_post_types' );

# Filter the "enter title here" text.
add_filter( 'enter_title_here', 'mc_enter_title_here', 10, 2 );

# Filter the bulk and post updated messages.
add_filter( 'bulk_post_updated_messages', 'mc_bulk_post_updated_messages', 5, 2 );
add_filter( 'post_updated_messages',      'mc_post_updated_messages',      5    );

/**
 * Returns the name of the project post type.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_project_post_type() {

	return apply_filters( 'mc_get_project_post_type', 'portfolio_project' );
}

/**
 * Returns the capabilities for the project post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function mc_get_project_capabilities() {

	$caps = array(

		// meta caps (don't assign these to roles)
		'edit_post'              => 'edit_portfolio_project',
		'read_post'              => 'read_portfolio_project',
		'delete_post'            => 'delete_portfolio_project',

		// primitive/meta caps
		'create_posts'           => 'create_portfolio_projects',

		// primitive caps used outside of map_meta_cap()
		'edit_posts'             => 'edit_portfolio_projects',
		'edit_others_posts'      => 'edit_others_portfolio_projects',
		'publish_posts'          => 'publish_portfolio_projects',
		'read_private_posts'     => 'read_private_portfolio_projects',

		// primitive caps used inside of map_meta_cap()
		'read'                   => 'read',
		'delete_posts'           => 'delete_portfolio_projects',
		'delete_private_posts'   => 'delete_private_portfolio_projects',
		'delete_published_posts' => 'delete_published_portfolio_projects',
		'delete_others_posts'    => 'delete_others_portfolio_projects',
		'edit_private_posts'     => 'edit_private_portfolio_projects',
		'edit_published_posts'   => 'edit_published_portfolio_projects'
	);

	return apply_filters( 'mc_get_project_capabilities', $caps );
}

/**
 * Returns the labels for the project post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function mc_get_project_labels() {

	$labels = array(
		'name'                  => __( 'Projects',                   'music-composition' ),
		'singular_name'         => __( 'Project',                    'music-composition' ),
		'menu_name'             => __( 'Portfolio',                  'music-composition' ),
		'name_admin_bar'        => __( 'Project',                    'music-composition' ),
		'add_new'               => __( 'New Project',                'music-composition' ),
		'add_new_item'          => __( 'Add New Project',            'music-composition' ),
		'edit_item'             => __( 'Edit Project',               'music-composition' ),
		'new_item'              => __( 'New Project',                'music-composition' ),
		'view_item'             => __( 'View Project',               'music-composition' ),
		'view_items'            => __( 'View Projects',              'music-composition' ),
		'search_items'          => __( 'Search Projects',            'music-composition' ),
		'not_found'             => __( 'No projects found',          'music-composition' ),
		'not_found_in_trash'    => __( 'No projects found in trash', 'music-composition' ),
		'all_items'             => __( 'Projects',                   'music-composition' ),
		'featured_image'        => __( 'Project Image',              'music-composition' ),
		'set_featured_image'    => __( 'Set project image',          'music-composition' ),
		'remove_featured_image' => __( 'Remove project image',       'music-composition' ),
		'use_featured_image'    => __( 'Use as project image',       'music-composition' ),
		'insert_into_item'      => __( 'Insert into project',        'music-composition' ),
		'uploaded_to_this_item' => __( 'Uploaded to this project',   'music-composition' ),
		'filter_items_list'     => __( 'Filter projects list',       'music-composition' ),
		'items_list_navigation' => __( 'Projects list navigation',   'music-composition' ),
		'items_list'            => __( 'Projects list',              'music-composition' ),

		// Custom labels b/c WordPress doesn't have anything to handle this.
		'archive_title'         => mc_get_portfolio_title(),
	);

	return apply_filters( 'mc_get_project_labels', $labels );
}

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function mc_register_post_types() {

	// Set up the arguments for the portfolio project post type.
	$project_args = array(
		'description'         => mc_get_portfolio_description(),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-portfolio',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => mc_get_portfolio_rewrite_base(),
		'query_var'           => mc_get_project_post_type(),
		'capability_type'     => 'portfolio_project',
		'map_meta_cap'        => true,
		'capabilities'        => mc_get_project_capabilities(),
		'labels'              => mc_get_project_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'       => mc_get_project_rewrite_slug(),
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		// What features the post type supports.
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'post-formats',

			// Theme/Plugin feature support.
			'custom-background', // Custom Background Extended
			'custom-header',     // Custom Header Extended
		)
	);

	// Register the post types.
	register_post_type( mc_get_project_post_type(), apply_filters( 'mc_project_post_type_args', $project_args ) );
}

/**
 * Custom "enter title here" text.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @param  object  $post
 * @return string
 */
function mc_enter_title_here( $title, $post ) {

	return mc_get_project_post_type() === $post->post_type ? esc_html__( 'Enter project title', 'music-composition' ) : $title;
}

/**
 * Adds custom post updated messages on the edit post screen.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @global object $post
 * @global int    $post_ID
 * @return array
 */
function mc_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$project_type = mc_get_project_post_type();

	if ( $project_type !== $post->post_type )
		return $messages;

	// Get permalink and preview URLs.
	$permalink   = get_permalink( $post_ID );
	$preview_url = get_preview_post_link( $post );

	// Translators: Scheduled project date format. See http://php.net/date
	$scheduled_date = date_i18n( __( 'M j, Y @ H:i', 'music-composition' ), strtotime( $post->post_date ) );

	// Set up view links.
	$preview_link   = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>', esc_url( $preview_url ), esc_html__( 'Preview project', 'music-composition' ) );
	$scheduled_link = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>', esc_url( $permalink ),   esc_html__( 'Preview project', 'music-composition' ) );
	$view_link      = sprintf( ' <a href="%1$s">%2$s</a>',                 esc_url( $permalink ),   esc_html__( 'View project',    'music-composition' ) );

	// Post updated messages.
	$messages[ $project_type ] = array(
		 1 => esc_html__( 'Project updated.', 'music-composition' ) . $view_link,
		 4 => esc_html__( 'Project updated.', 'music-composition' ),
		 // Translators: %s is the date and time of the revision.
		 5 => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Project restored to revision from %s.', 'music-composition' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => esc_html__( 'Project published.', 'music-composition' ) . $view_link,
		 7 => esc_html__( 'Project saved.', 'music-composition' ),
		 8 => esc_html__( 'Project submitted.', 'music-composition' ) . $preview_link,
		 9 => sprintf( esc_html__( 'Project scheduled for: %s.', 'music-composition' ), "<strong>{$scheduled_date}</strong>" ) . $scheduled_link,
		10 => esc_html__( 'Project draft updated.', 'music-composition' ) . $preview_link,
	);

	return $messages;
}

/**
 * Adds custom bulk post updated messages on the manage projects screen.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @param  array  $counts
 * @return array
 */
function mc_bulk_post_updated_messages( $messages, $counts ) {

	$type = mc_get_project_post_type();

	$messages[ $type ]['updated']   = _n( '%s project updated.',                             '%s projects updated.',                               $counts['updated'],   'music-composition' );
	$messages[ $type ]['locked']    = _n( '%s project not updated, somebody is editing it.', '%s projects not updated, somebody is editing them.', $counts['locked'],    'music-composition' );
	$messages[ $type ]['deleted']   = _n( '%s project permanently deleted.',                 '%s projects permanently deleted.',                   $counts['deleted'],   'music-composition' );
	$messages[ $type ]['trashed']   = _n( '%s project moved to the Trash.',                  '%s projects moved to the trash.',                    $counts['trashed'],   'music-composition' );
	$messages[ $type ]['untrashed'] = _n( '%s project restored from the Trash.',             '%s projects restored from the trash.',               $counts['untrashed'], 'music-composition' );

	return $messages;
}
