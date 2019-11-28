<?php
/**
 * The Music Composition Plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jim-duke.github.io/music-composition/
 * @package           Music_Composition
 * @since             1.0.0
 * @version           1.0.0
 * @author            Jim Duke <jim@dukeboys.org>
 * @copyright         Copyright (c) 2019, Jim Duke
 * @license           http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @wordpress-plugin
 * Plugin Name:       Music Composition
 * Plugin URI:        https://jim-duke.github.io/music-composition/
 * Description:       Sheet Music Library manager for WordPress to manage, edit, and create new composition items in a library.
 * Version:           1.0.0
 * Author:            Jim Duke
 * Author URI:        https://jim.dukeboys.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       music-composition
 * Domain Path:       /languages
 *
 * The Music Composition plugin was created as the core content type needed for a website I was
 * was developing to manage a library of sheet music and associated media.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'MUSIC_COMPOSITION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-music-composition-activator.php
 */
function activate_music_composition() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-music-composition-activator.php';
    Music_Composition_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-music-composition-deactivator.php
 */
function deactivate_music_composition() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-music-composition-deactivator.php';
    Music_Composition_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_music_composition' );
register_deactivation_hook( __FILE__, 'deactivate_music_composition' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-music-composition.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_music_composition() {
    $plugin = new Music_Composition();
    $plugin->run();
}

/*
 * Now that everything is setup and registered; run the bootstrap code.
 */
run_music_composition();
