<?php
/**
 * Admin-related functions and filters.
 *
 * @package    MusicComposition
 * @subpackage Admin
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register scripts and styles.
add_action( 'admin_enqueue_scripts', 'mc_admin_register_scripts', 0 );

# Registers project details box sections, controls, and settings.
add_action( 'butterbean_register', 'mc_project_details_register', 5, 2 );

# Filter post format support for projects.
add_action( 'load-post.php',     'mc_post_format_support_filter' );
add_action( 'load-post-new.php', 'mc_post_format_support_filter' );
add_action( 'load-edit.php',     'mc_post_format_support_filter' );

/**
 * Registers admin scripts.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_admin_register_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'mc-edit-project', mc_plugin()->js_uri . "edit-project{$min}.js", array( 'jquery', 'wp-util' ), '', true );

	// Localize our script with some text we want to pass in.
	$i18n = array(
		'label_sticky'     => esc_html__( 'Sticky',     'music-composition' ),
		'label_not_sticky' => esc_html__( 'Not Sticky', 'music-composition' ),
	);

	wp_localize_script( 'mc-edit-project', 'mc_i18n', $i18n );
}

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_project_details_register( $butterbean, $post_type ) {

	if ( $post_type !== mc_get_project_post_type() )
		return;

	$butterbean->register_manager( 'mc-project',
		array(
			'post_type' => $post_type,
			'context'   => 'normal',
			'priority'  => 'high',
			'label'     => esc_html__( 'Project Details:', 'music-composition' )
		)
	);

	$manager = $butterbean->get_manager( 'mc-project' );

	/* === Register Sections === */

	// General section.
	$manager->register_section( 'general',
		array(
			'label' => esc_html__( 'General', 'music-composition' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

	// Date section.
	$manager->register_section( 'date',
		array(
			'label' => esc_html__( 'Date', 'music-composition' ),
			'icon'  => 'dashicons-clock'
		)
	);

	// Description section.
	$manager->register_section( 'description',
		array(
			'label' => esc_html__( 'Description', 'music-composition' ),
			'icon'  => 'dashicons-edit'
		)
	);

	/* === Register Fields === */

	$url_args = array(
		'type'        => 'url',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'https://jim.dukeboys.org' ),
		'label'       => esc_html__( 'URL', 'music-composition' ),
		'description' => esc_html__( 'Enter the URL of the project Web page.', 'music-composition' )
	);

	$client_args = array(
		'type'        => 'text',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Jane Doe', 'music-composition' ) ),
		'label'       => esc_html__( 'Client', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the name of the client for the project.', 'music-composition' )
	);

	$location_args = array(
		'type'        => 'text',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Highland Home, AL', 'music-composition' ) ),
		'label'       => esc_html__( 'Location', 'music-composition' ),
		'description' => esc_html__( 'Enter the physical location of the project.', 'music-composition' )
	);

	$start_date_args = array(
		'type'        => 'datetime',
		'section'     => 'date',
		'show_time'   => false,
		'label'       => esc_html__( 'Start Date', 'music-composition' ),
		'description' => esc_html__( 'Select the date the project began.', 'music-composition' )
	);

	$end_date_args = array(
		'type'        => 'datetime',
		'section'     => 'date',
		'show_time'   => false,
		'label'       => esc_html__( 'End Date', 'music-composition' ),
		'description' => esc_html__( 'Select the date the project was completed.', 'music-composition' )
	);

	$manager->register_field( 'url',      $url_args,      array( 'sanitize_callback' => 'esc_url_raw'       ) );
	$manager->register_field( 'client',   $client_args,   array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_field( 'location', $location_args, array( 'sanitize_callback' => 'wp_strip_all_tags' ) );

	$manager->register_field( 'start_date', $start_date_args, array( 'type' => 'datetime' ) );
	$manager->register_field( 'end_date',   $end_date_args,   array( 'type' => 'datetime' ) );

	/* === Register Controls === */

	$excerpt_args = array(
		'type'        => 'excerpt',
		'section'     => 'description',
		'label'       => esc_html__( 'Description', 'music-composition' ),
		'description' => esc_html__( 'Write a short description (excerpt) of the project.', 'music-composition' )
	);

	$manager->register_control( 'excerpt', $excerpt_args );
}

/**
 * Helper function for getting the correct slug for the settings page.  This is useful
 * for add-on plugins that need to add custom setting sections or fields to the settings
 * screen for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_settings_page_slug() {

	return sprintf( '%s_page_mc-settings', mc_get_project_post_type() );
}

/**
 * Returns an array of post formats allowed for the project post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function mc_get_allowed_project_formats() {

	return apply_filters( 'mc_get_allowed_project_formats', array( 'audio', 'gallery', 'image', 'video' ) );
}

/**
 * If a theme supports post formats, limit project to only the audio, image,
 * gallery, and video formats.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_post_format_support_filter() {

	$screen       = get_current_screen();
	$project_type = mc_get_project_post_type();

	// Bail if not on the projects screen.
	if ( empty( $screen->post_type ) || $project_type !== $screen->post_type )
		return;

	// Check if the current theme supports formats.
	if ( current_theme_supports( 'post-formats' ) ) {

		$formats = get_theme_support( 'post-formats' );

		// If we have formats, add theme support for only the allowed formats.
		if ( isset( $formats[0] ) ) {
			$new_formats = array_intersect( $formats[0], mc_get_allowed_project_formats() );

			// Remove post formats support.
			remove_theme_support( 'post-formats' );

			// If the theme supports the allowed formats, add support for them.
			if ( $new_formats )
				add_theme_support( 'post-formats', $new_formats );
		}
	}

	// Filter the default post format.
	add_filter( 'option_default_post_format', 'mc_default_post_format_filter', 95 );
}

/**
 * Filters the default post format to make sure that it's in our list of supported formats.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $format
 * @return string
 */
function mc_default_post_format_filter( $format ) {

	return in_array( $format, mc_get_allowed_project_formats() ) ? $format : 'standard';
}

/**
 * Help sidebar for all of the help tabs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_help_sidebar_text() {

	// Get docs and help links.
	$docs_link = sprintf( '<li><a href="https://jim.dukeboys.org/plugins/music-composition/docs">%s</a></li>', esc_html__( 'Documentation', 'music-composition' ) );
	$help_link = sprintf( '<li><a href="https://jim.dukeboys.org/plugins/music-composition/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'music-composition' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'music-composition' ),
		$docs_link,
		$help_link
	);
}
