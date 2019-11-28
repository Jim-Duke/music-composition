<?php
/**
 * New/Edit composition admin screen.
 *
 * @package    MusicComposition
 * @subpackage Admin
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Project edit screen functionality.
 *
 * @since  1.0.0
 * @access public
 */
final class MC_Composition_Edit {

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );

		// Add the help tabs.
		add_action( 'mc_load_composition_edit', array( $this, 'add_help_tabs' ) );
	}

	/**
	 * Runs on the page load. Checks if we're viewing the composition post type and adds
	 * the appropriate actions/filters for the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$screen       = get_current_screen();
		$composition_type = mc_get_composition_post_type();

		// Bail if not on the compositions screen.
		if ( empty( $screen->post_type ) || $composition_type !== $screen->post_type )
			return;

		// Custom action for loading the edit composition screen.
		do_action( 'mc_load_composition_edit' );

		// Enqueue scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Print custom styles.
		add_action( 'admin_head', array( $this, 'print_styles' ) );

		// Add/Remove meta boxes.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Add custom option to the publish/submit meta box.
		add_action( 'post_submitbox_misc_actions', array( $this, 'submitbox_misc_actions' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ) );

		// Filter the post author drop-down.
		add_filter( 'wp_dropdown_users_args', array( $this, 'dropdown_users_args' ), 10, 2 );
	}

	/**
	 * Load scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		wp_enqueue_script( 'mc-edit-composition' );
	}

	/**
	 * Print styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function print_styles() { ?>

		<style type="text/css">
			.misc-pub-project-sticky .dashicons {
				color: rgb( 130, 135, 140 );
			}

			.misc-pub-project-sticky label {
				display: block;
				margin:  8px 0 8px 2px;
			}
		</style>
	<?php }

	/**
	 * Adds/Removes meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $post_type
	 * @return void
	 */
	public function add_meta_boxes( $post_type ) {

		remove_meta_box( 'postexcerpt', $post_type, 'normal' );
	}

	/**
	 * Callback on the `post_submitbox_misc_actions` hook (submit meta box). This handles
	 * the output of the sticky composition feature.
	 *
	 * @note   Prior to WP 4.4.0, the `$post` parameter was not passed.
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @return void
	 */
	public function submitbox_misc_actions( $post = '' ) {

		// Pre-4.4.0 compatibility.
		if ( ! $post ) {
			global $post;
		}

		// Get the post type object.
		$post_type_object = get_post_type_object( mc_get_composition_post_type() );

		// Is the composition sticky?
		$is_sticky = mc_is_composition_sticky( $post->ID );

		// Set the label based on whether the composition is sticky.
		$label = $is_sticky ? esc_html__( 'Sticky', 'music-composition' ) : esc_html__( 'Not Sticky', 'music-composition' ); ?>

		<div class="misc-pub-section curtime misc-pub-composition-sticky">

			<?php wp_nonce_field( 'mc_composition_publish_box_nonce', 'mc_composition_publish_box' ); ?>

			<i class="dashicons dashicons-sticky"></i>
			<?php printf( esc_html__( 'Sticky: %s', 'music-composition' ), "<strong class='mc-sticky-status'>{$label}</strong>" ); ?>

			<?php if ( current_user_can( $post_type_object->cap->publish_posts ) ) : ?>

				<a href="#mc-sticky-edit" class="mc-edit-sticky"><span aria-hidden="true"><?php esc_html_e( 'Edit', 'music-composition' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit sticky status', 'music-composition' ); ?></span></a>

				<div id="mc-sticky-edit" class="hide-if-js">
					<label>
						<input type="checkbox" name="mc_composition_sticky" id="mc-composition-sticky" <?php checked( $is_sticky ); ?> value="true" />
						<?php esc_html_e( 'Stick to the library page', 'music-composition' ); ?>
					</label>
					<a href="#mc-composition-sticky" class="mc-save-sticky hide-if-no-js button"><?php esc_html_e( 'OK', 'music-composition' ); ?></a>
					<a href="#mc-composition-sticky" class="mc-cancel-sticky hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'music-composition' ); ?></a>
				</div><!-- #mc-sticky-edit -->

			<?php endif; ?>

		</div><!-- .misc-pub-composition-sticky -->
	<?php }

	/**
	 * Output the composition details box.  TBD - decide whether we need to keep this.
	 *
	 * @since      1.0.0
	 * @deprecated 2.0.0
	 * @access     public
	 * @param      object  $post
	 * @return     void
	 */
	public function composition_details_box( $post ) {}

	/**
	 * Save composition details settings on post save.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function update( $post_id ) {

		// Verify the nonce.
		if ( ! isset( $_POST['mc_composition_publish_box'] ) || ! wp_verify_nonce( $_POST['mc_composition_publish_box'], 'mc_composition_publish_box_nonce' ) )
			return;

		// Is the sticky checkbox checked?
		$should_stick = ! empty( $_POST['mc_composition_sticky'] );

		// If checked, add the composition if it is not sticky.
		if ( $should_stick && ! mc_is_composition_sticky( $post_id ) )
			mc_add_sticky_composition( $post_id );

		// If not checked, remove the composition if it is sticky.
		elseif ( ! $should_stick && mc_is_composition_sticky( $post_id ) )
			mc_remove_sticky_composition( $post_id );
	}

	/**
	 * Filter on the post author drop-down (used in the "Author" meta box) to only show users
	 * of roles that have the correct capability for editing compositions..
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $args
	 * @param  array   $r
	 * @global object  $wp_roles
	 * @global object  $post
	 * @return array
	 */
	function dropdown_users_args( $args, $r ) {
		global $wp_roles, $post;

		// Check that this is the correct drop-down.
		if ( 'post_author_override' === $r['name'] && mc_get_composition_post_type() === $post->post_type ) {

			$roles = array();

			// Loop through the available roles.
			foreach ( $wp_roles->roles as $name => $role ) {

				// Get the edit posts cap.
				$cap = get_post_type_object( mc_get_composition_post_type() )->cap->edit_posts;

				// If the role is granted the edit posts cap, add it.
				if ( isset( $role['capabilities'][ $cap ] ) && true === $role['capabilities'][ $cap ] )
					$roles[] = $name;
			}

			// If we have roles, change the args to only get users of those roles.
			if ( $roles ) {
				$args['who']      = '';
				$args['role__in'] = $roles;
			}
		}

		return $args;
	}

	/**
	 * Adds custom help tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		$screen = get_current_screen();

		// Title and editor help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'title_editor',
				'title'    => esc_html__( 'Title and Editor', 'music-composition' ),
				'callback' => array( $this, 'help_tab_title_editor' )
			)
		);

		// Composition details help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'project_details',
				'title'    => esc_html__( 'Composition Details', 'music-composition' ),
				'callback' => array( $this, 'help_tab_composition_details' )
			)
		);

		// Set the help sidebar.
		$screen->set_help_sidebar( mc_get_help_sidebar_text() );
	}

	/**
	 * Displays the title and editor help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_title_editor() { ?>

		<ul>
			<li><?php _e( "<strong>Title:</strong> Enter a title for your composition. After you enter a title, you'll see the permalink below, which you can edit.", 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Editor:</strong> The editor allows you to add or edit content for your composition. You can insert text, media, or shortcodes.', 'music-composition' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the composition details help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_composition_details() { ?>

		<p>
			<?php esc_html_e( 'The Composition Details meta box allows you to customize the details of your composition. All fields are optional.', 'music-composition' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>URL:</strong> The URL to the Web site or page associated with the composition, such as a client Web site.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Client:</strong> The name of the client the composition was built for.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Location:</strong> A physical location where the composition took place (e.g., Highland Home, AL, USA).', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Start Date:</strong> The date the composition began.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>End Date:</strong> The date the composition was completed.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Description:</strong> A short summary of the composition. Some themes may show this on archive pages.', 'music-composition' ); ?></li>
		</ul>
	<?php }

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new self;

		return $instance;
	}
}

MC_Composition_Edit::get_instance();
