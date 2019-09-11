<?php
/**
 * Plugin rewrite functions.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Add custom rewrite rules.
add_action( 'init', 'mc_rewrite_rules', 5 );

/**
 * Adds custom rewrite rules for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function mc_rewrite_rules() {

	$project_type = mc_get_project_post_type();
	$author_slug  = mc_get_author_rewrite_slug();

	// Where to place the rewrite rules.  If no rewrite base, put them at the bottom.
	$after = mc_get_author_rewrite_base() ? 'top' : 'bottom';

	add_rewrite_rule( $author_slug . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?post_type=' . $project_type . '&author_name=$matches[1]&paged=$matches[2]', $after );
	add_rewrite_rule( $author_slug . '/([^/]+)/?$',                   'index.php?post_type=' . $project_type . '&author_name=$matches[1]',                   $after );
}

/**
 * Returns the project rewrite slug used for single projects.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_project_rewrite_slug() {
	$library_base = mc_get_library_rewrite_base();
	$project_base   = mc_get_project_rewrite_base();

	$slug = $project_base ? trailingslashit( $library_base ) . $project_base : $library_base;

	return apply_filters( 'mc_get_project_rewrite_slug', $slug );
}

/**
 * Returns the category rewrite slug used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_category_rewrite_slug() {
	$library_base = mc_get_library_rewrite_base();
	$category_base  = mc_get_category_rewrite_base();

	$slug = $category_base ? trailingslashit( $library_base ) . $category_base : $library_base;

	return apply_filters( 'mc_get_category_rewrite_slug', $slug );
}

/**
 * Returns the tag rewrite slug used for tag archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_tag_rewrite_slug() {
	$library_base = mc_get_library_rewrite_base();
	$tag_base       = mc_get_tag_rewrite_base();

	$slug = $tag_base ? trailingslashit( $library_base ) . $tag_base : $library_base;

	return apply_filters( 'mc_get_tag_rewrite_slug', $slug );
}

/**
 * Returns the author rewrite slug used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_author_rewrite_slug() {
	$library_base = mc_get_library_rewrite_base();
	$author_base  = mc_get_author_rewrite_base();

	$slug = $author_base ? trailingslashit( $library_base ) . $author_base : $library_base;

	return apply_filters( 'mc_get_author_rewrite_slug', $slug );
}
