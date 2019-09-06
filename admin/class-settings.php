<?php
/**
 * Plugin settings screen.
 *
 * @package    MusicComposition
 * @subpackage Admin
 * @author     Jim Duke <jim@dukeboys.org>
 * @copyright  Copyright (c) 2019, Jim Duke
 * @link       https://jim.dukeboys.org/plugins/music-composition
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Sets up and handles the plugin settings screen.
 *
 * @since  1.0.0
 * @access public
 */
final class MC_Settings_Page {

	/**
	 * Settings page name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $settings_page = '';

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Sets up custom admin menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		// Create the settings page.
		$this->settings_page = add_submenu_page(
			'edit.php?post_type=' . mc_get_project_post_type(),
			esc_html__( 'Portfolio Settings', 'music-composition' ),
			esc_html__( 'Settings',           'music-composition' ),
			apply_filters( 'mc_settings_capability', 'manage_options' ),
			'mc-settings',
			array( $this, 'settings_page' )
		);

		if ( $this->settings_page ) {

			// Register settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Add help tabs.
			add_action( "load-{$this->settings_page}", array( $this, 'add_help_tabs' ) );
		}
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	function register_settings() {

		// Register the setting.
		register_setting( 'mc_settings', 'mc_settings', array( $this, 'validate_settings' ) );

		/* === Settings Sections === */

		add_settings_section( 'general',    esc_html__( 'General Settings', 'music-composition' ), array( $this, 'section_general'    ), $this->settings_page );
		add_settings_section( 'permalinks', esc_html__( 'Permalinks',       'music-composition' ), array( $this, 'section_permalinks' ), $this->settings_page );

		/* === Settings Fields === */

		// General section fields
		add_settings_field( 'portfolio_title',       esc_html__( 'Title',       'music-composition' ), array( $this, 'field_portfolio_title'       ), $this->settings_page, 'general' );
		add_settings_field( 'portfolio_description', esc_html__( 'Description', 'music-composition' ), array( $this, 'field_portfolio_description' ), $this->settings_page, 'general' );

		// Permalinks section fields.
		add_settings_field( 'portfolio_rewrite_base', esc_html__( 'Portfolio Base', 'music-composition' ), array( $this, 'field_portfolio_rewrite_base' ), $this->settings_page, 'permalinks' );
		add_settings_field( 'project_rewrite_base',   esc_html__( 'Project Slug',   'music-composition' ), array( $this, 'field_project_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'category_rewrite_base',  esc_html__( 'Category Slug',  'music-composition' ), array( $this, 'field_category_rewrite_base'  ), $this->settings_page, 'permalinks' );
		add_settings_field( 'tag_rewrite_base',       esc_html__( 'Tag Slug',       'music-composition' ), array( $this, 'field_tag_rewrite_base'       ), $this->settings_page, 'permalinks' );
		add_settings_field( 'author_rewrite_base',    esc_html__( 'Author Slug',    'music-composition' ), array( $this, 'field_author_rewrite_base'    ), $this->settings_page, 'permalinks' );
	}

	/**
	 * Validates the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $input
	 * @return array
	 */
	function validate_settings( $settings ) {

		// Text boxes.
		$settings['portfolio_rewrite_base'] = $settings['portfolio_rewrite_base'] ? trim( strip_tags( $settings['portfolio_rewrite_base'] ), '/' ) : 'portfolio';
		$settings['project_rewrite_base']   = $settings['project_rewrite_base']   ? trim( strip_tags( $settings['project_rewrite_base']   ), '/' ) : '';
		$settings['category_rewrite_base']  = $settings['category_rewrite_base']  ? trim( strip_tags( $settings['category_rewrite_base']  ), '/' ) : '';
		$settings['tag_rewrite_base']       = $settings['tag_rewrite_base']       ? trim( strip_tags( $settings['tag_rewrite_base']       ), '/' ) : '';
		$settings['author_rewrite_base']    = $settings['author_rewrite_base']    ? trim( strip_tags( $settings['author_rewrite_base']    ), '/' ) : '';
		$settings['portfolio_title']        = $settings['portfolio_title']        ? strip_tags( $settings['portfolio_title'] )                     : esc_html__( 'Portfolio', 'music-composition' );

		// Kill evil scripts.
		$settings['portfolio_description'] = stripslashes( wp_filter_post_kses( addslashes( $settings['portfolio_description'] ) ) );

		/* === Handle Permalink Conflicts ===*/

		// No project or category base, projects win.
		if ( ! $settings['project_rewrite_base'] && ! $settings['category_rewrite_base'] )
			$settings['category_rewrite_base'] = 'categories';

		// No project or tag base, projects win.
		if ( ! $settings['project_rewrite_base'] && ! $settings['tag_rewrite_base'] )
			$settings['tag_rewrite_base'] = 'tags';

		// No project or author base, projects win.
		if ( ! $settings['project_rewrite_base'] && ! $settings['author_rewrite_base'] )
			$settings['author_rewrite_base'] = 'authors';

		// No category or tag base, categories win.
		if ( ! $settings['category_rewrite_base'] && ! $settings['tag_rewrite_base'] )
			$settings['tag_rewrite_base'] = 'tags';

		// No category or author base, categories win.
		if ( ! $settings['category_rewrite_base'] && ! $settings['author_rewrite_base'] )
			$settings['author_rewrite_base'] = 'authors';

		// No author or tag base, authors win.
		if ( ! $settings['author_rewrite_base'] && ! $settings['tag_rewrite_base'] )
			$settings['tag_rewrite_base'] = 'tags';

		// Return the validated/sanitized settings.
		return $settings;
	}

	/**
	 * General section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_general() { ?>

		<p class="description">
			<?php esc_html_e( 'General portfolio settings for your site.', 'music-composition' ); ?>
		</p>
	<?php }

	/**
	 * Portfolio title field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_title() { ?>

		<label>
			<input type="text" class="regular-text" name="mc_settings[portfolio_title]" value="<?php echo esc_attr( mc_get_portfolio_title() ); ?>" />
			<br />
			<span class="description"><?php esc_html_e( 'The name of your portfolio. May be used for the portfolio page title and other places, depending on your theme.', 'music-composition' ); ?></span>
		</label>
	<?php }

	/**
	 * Portfolio description field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_description() {

		wp_editor(
			mc_get_portfolio_description(),
			'mc_portfolio_description',
			array(
				'textarea_name'    => 'mc_settings[portfolio_description]',
				'drag_drop_upload' => true,
				'editor_height'    => 150
			)
		); ?>

		<p>
			<span class="description"><?php esc_html_e( 'Your portfolio description. This may be shown by your theme on the portfolio page.', 'music-composition' ); ?></span>
		</p>
	<?php }

	/**
	 * Permalinks section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_permalinks() { ?>

		<p class="description">
			<?php esc_html_e( 'Set up custom permalinks for the portfolio section on your site.', 'music-composition' ); ?>
		</p>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="mc_settings[portfolio_rewrite_base]" value="<?php echo esc_attr( mc_get_portfolio_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_project_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( mc_get_portfolio_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="mc_settings[project_rewrite_base]" value="<?php echo esc_attr( mc_get_project_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_category_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( mc_get_portfolio_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="mc_settings[category_rewrite_base]" value="<?php echo esc_attr( mc_get_category_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_tag_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( mc_get_portfolio_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="mc_settings[tag_rewrite_base]" value="<?php echo esc_attr( mc_get_tag_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Author rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_author_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( mc_get_portfolio_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="mc_settings[author_rewrite_base]" value="<?php echo esc_attr( mc_get_author_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Renders the settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_page() {

		// Flush the rewrite rules if the settings were updated.
		if ( isset( $_GET['settings-updated'] ) )
			flush_rewrite_rules(); ?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Portfolio Settings', 'music-composition' ); ?></h1>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'mc_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'music-composition' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	/**
	 * Adds help tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		// Get the current screen.
		$screen = get_current_screen();

		// General settings help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'general',
				'title'    => esc_html__( 'General Settings', 'music-composition' ),
				'callback' => array( $this, 'help_tab_general' )
			)
		);

		// Permalinks settings help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'permalinks',
				'title'    => esc_html__( 'Permalinks', 'music-composition' ),
				'callback' => array( $this, 'help_tab_permalinks' )
			)
		);

		// Set the help sidebar.
		$screen->set_help_sidebar( mc_get_help_sidebar_text() );
	}

	/**
	 * Displays the general settings help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_general() { ?>

		<ul>
			<li><?php _e( '<strong>Title:</strong> Allows you to set the title for the portfolio section on your site. This is general shown on the portfolio projects archive, but themes and other plugins may use it in other ways.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Description:</strong> This is the description for your portfolio. Some themes may display this on the portfolio projects archive.', 'music-composition' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the permalinks help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_permalinks() { ?>

		<ul>
			<li><?php _e( '<strong>Portfolio Base:</strong> The primary URL for the portfolio section on your site. It lists your portfolio projects.', 'music-composition' ); ?></li>
			<li>
				<?php _e( '<strong>Project Slug:</strong> The slug for single portfolio projects. You can use something custom, leave this field empty, or use one of the following tags:', 'music-composition' ); ?>
				<ul>
					<li><?php printf( esc_html__( '%s - The project author name.', 'music-composition' ), '<code>%author%</code>' ); ?></li>
					<li><?php printf( esc_html__( '%s - The project category.', 'music-composition' ), '<code>%' . mc_get_category_taxonomy() . '%</code>' ); ?></li>
					<li><?php printf( esc_html__( '%s - The project tag.', 'music-composition' ), '<code>%' . mc_get_tag_taxonomy() . '%</code>' ); ?></li>
				</ul>
			</li>
			<li><?php _e( '<strong>Category Slug:</strong> The base slug used for portfolio category archives.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Tag Slug:</strong> The base slug used for portfolio tag archives.', 'music-composition' ); ?></li>
			<li><?php _e( '<strong>Author Slug:</strong> The base slug used for portfolio author archives.', 'music-composition' ); ?></li>
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

MC_Settings_Page::get_instance();
