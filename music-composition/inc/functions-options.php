<?php
/**
 * Plugin options functions.
 *
 * @package    MusicComposition
 * @subpackage Includes
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Returns the library title.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_library_title() {
	return apply_filters( 'mc_get_library_title', mc_get_setting( 'library_title' ) );
}

/**
 * Returns the library description.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_library_description() {
	return apply_filters( 'mc_get_library_description', mc_get_setting( 'library_description' ) );
}

/**
 * Returns the library rewrite base. Used for the project archive and as a prefix for taxonomy,
 * author, and any other slugs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_library_rewrite_base() {
	return apply_filters( 'mc_get_library_rewrite_base', mc_get_setting( 'library_rewrite_base' ) );
}

/**
 * Returns the project rewrite base. Used for single projects.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_project_rewrite_base() {
	return apply_filters( 'mc_get_project_rewrite_base', mc_get_setting( 'project_rewrite_base' ) );
}

/**
 * Returns the category rewrite base. Used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_category_rewrite_base() {
	return apply_filters( 'mc_get_category_rewrite_base', mc_get_setting( 'category_rewrite_base' ) );
}

/**
 * Returns the tag rewrite base. Used for tag archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_tag_rewrite_base() {
	return apply_filters( 'mc_get_tag_rewrite_base', mc_get_setting( 'tag_rewrite_base' ) );
}

/**
 * Returns the author rewrite base. Used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function mc_get_author_rewrite_base() {
	return apply_filters( 'mc_get_author_rewrite_base', mc_get_setting( 'author_rewrite_base' ) );
}

/**
 * Returns the default category term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function mc_get_default_category() {
	return apply_filters( 'mc_get_default_category', 0 );
}

/**
 * Returns the default tag term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function mc_get_default_tag() {
	return apply_filters( 'mc_get_default_tag', 0 );
}

/**
 * Returns a plugin setting.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $setting
 * @return mixed
 */
function mc_get_setting( $setting ) {

	$defaults = mc_get_default_settings();
	$settings = wp_parse_args( get_option( 'mc_settings', $defaults ), $defaults );

	return isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
}

/**
 * Returns the default settings for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
function mc_get_default_settings() {

	$settings = array(
		'library_title'        => __( 'Library', 'music-composition' ),
		'library_description'  => '',
		'library_rewrite_base' => 'library',
		'project_rewrite_base'   => 'projects',
		'category_rewrite_base'  => 'categories',
		'tag_rewrite_base'       => 'tags',
		'author_rewrite_base'    => 'authors'
	);

	return $settings;
}
