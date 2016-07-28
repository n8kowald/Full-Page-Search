<?php
/*
* Plugin Name:  Full Page Search Overlay
* Plugin URI: http://www.yikesinc.com
* Version: 1.0.0
* Description: Creates a popup/overlay for a full screen search.
* Author: Evan Herman, Tracy Levesque, Yikes, Inc.
* Author URI: http://www.yikesinc.com
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class YIKES_Full_Page_Search {
	// Define a global
	public $yikes_nav_menu_class;

	// Constructor
	public function __construct() {
		/**
		 * 	Define path constant to our plugin directory.
		 * 	@since 1.0.0
		 */
		if ( ! defined( 'YIKES_FPS_PATH' ) ) {
			define( 'YIKES_FPS_PATH' , trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}
		/**
		 * 	Define URL constant to our plugin directory.
		 * 	@since 1.0.0
		 */
		if ( ! defined( 'YIKES_FPS_URL' ) ) {
			define( 'YIKES_FPS_URL' , trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
		/**
		 * Define a constant so we know our class is being used
		 * Initially set to false, and re-defined inside of yikes-full-page-search-menu-walker.php
		 * when the class is actually used
		 */
		$this->yikes_nav_menu_class = false;
		// Include our nav menu walker class
		require_once YIKES_FPS_PATH . 'lib/classes/yikes-full-page-search-menu-walker.php';
		// Enqueue our scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
		// Append the search container to the page
		add_action( 'wp_footer', array( $this, 'generate_yikes_full_page_search_container' ) );
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'yikes_full_page_search_add_custom_nav_fields' ) );
		// Append the appropriate classes as needed
		add_filter( 'nav_menu_css_class', array( $this, 'yikes_full_page_search_filter_handler' ), 10, 4 );
		// Admin notice
		add_action( 'admin_notices', array( $this, 'generate_admin_notice_yikes_full_page_search' ) );
		// AJAX request to dismiss admin notice (works for logged in admins only, since it's admin side ;D)
		add_action( 'wp_ajax_dismiss_and_disable_yikes_full_page_search_notice', array( $this, 'dismiss_and_disable_yikes_full_page_search_notice' ) );
		// Uninstall hook - Handles uninstalling and deleting our data during plugin uninstall
		register_uninstall_hook( __FILE__, self::uninstall_yikes_full_page_search_plugin() );
	}

	/**
	 * Enqueue the scripts and styles for the search to function
	 * @since 1.0.0
	 */
	public function enqueue_scripts_and_styles() {
		wp_enqueue_script( 'full-page-search-js', YIKES_FPS_URL . 'lib/js/yikes-full-page-search.js', array( 'jquery' ), 'all', true );
		wp_enqueue_style( 'full-page-search-css', YIKES_FPS_URL . 'lib/css/yikes-full-page-search.css' );
	}

	/**
	 * Append the search container to the Footer of the site
	 * @return mixed HTML markup for the full page search container
	 * @since 1.0.0
	 */
	public function generate_yikes_full_page_search_container() {
		// Allow users to override our default template
		if ( file_exists( get_stylesheet_directory() . '/yikes-full-page-search/yikes-full-page-search-container.php' ) ) {
			require_once get_stylesheet_directory() . '/yikes-full-page-search/yikes-full-page-search-container.php';
		}
		// Require bundled template
		require_once YIKES_FPS_PATH . 'lib/templates/yikes-full-page-search-container.php';
	}

	/**
 * Add custom fields to $item nav object
 * in order to be used in custom Walker
 * @access      public
 * @since       1.0.0
 * @return      The new menu item and it's parameters
*/
	function yikes_full_page_search_add_custom_nav_fields( $menu_item ) {
		// Setup the parameters on the frontend
		// If the current menu item is a search link - disable the href attribute
		if ( get_post_meta( $menu_item->ID, '_set_search_link', true ) && 1 === absint( get_post_meta( $menu_item->ID, '_set_search_link', true ) ) ) {
			$menu_item->url = esc_url( home_url() );
			$menu_item->attr_title = __( 'Search this Site', 'full-page-search' );
		}
		return $menu_item;
	}

	/**
	 * Add the appropriate classes (WP 4.1+)
	 * @param  array    $classes  Original array of classes assigned to the menu item
	 * @param  object   $item     Menu item object, housing all of the parameters
	 * @param  array    $args     Original array of menu item arguments
	 * @param  integer  $depth    The depth to allow this menu item
	 * @return array              The new array of menu item class names
	 */
	public function yikes_full_page_search_filter_handler( $classes, $item, $args = array(), $depth = 1 ) {
		if ( isset( $item->set_search_link ) && 1 === absint( $item->set_search_link ) ) {
			$classes[] = 'yikes-full-page-search-toggle';
		}
		return $classes;
	}

	/**
	 * Generate a dismissible admin notice
	 * This notice lets the user know their theme (or another plugin) overrides our settings
	 * @since 1.0.0
	 * @return mixed HTML admin notice to use
	 */
	public function generate_admin_notice_yikes_full_page_search() {
		global $pagenow;
		// Confirm we are on the nav menus page
		if ( isset( $pagenow ) && 'nav-menus.php' === $pagenow ) {
			// Displays the admin notice when the class is not present, and the notice has not been dimissed before
			if ( ! defined( 'YIKES_FULL_PAGE_SEARCH_MENU_CLASS' ) && 'false' === get_option( 'dismiss_yikes_fullpage_search_menu_notice', 'false' ) ) {
			?>
				<!-- AJAX handler to make our notice dismisslbe -->
				<script type="text/javascript">
				jQuery( document ).ready( function() {
					jQuery( '.notice.is-dismissible' ).on( 'click', '.notice-dismiss', function( event ){
						// Run the ajax request to update dismiss_yikes_fullpage_search_menu_notice to true
						jQuery.post( ajaxurl, { 'action': 'dismiss_and_disable_yikes_full_page_search_notice' }, function( response ) {
							if ( ! response.success ) {
								/* Setup the error string */
								var error_text = '<span class="dashicons dashicons-dismiss"></span> <?php printf( __( "The notice was not dismissed. This generally occurs when there is a JavaScript error on the page. Please try again. If the error continues, please reach out to the %s.", "full-page-search" ), '<a href="https://yikesplugins.com/about/contact/" target="_blank">' . __( 'YIKES, Inc. support staff', 'full-page-search' ) . '</a>'); ?>';
								/* Setup the response HTML */
								var html = '<div class="notice notice-error yikes-error-response" style="display:none;"><p>' + error_text + '</p></div>';
								/* Append the response */
								jQuery( '.wrap > h1:first-child' ).after( html );
								/* Slide toggle it in */
								jQuery( '.yikes-error-response' ).slideToggle();
							}
						});
					});
				});
				</script>
				<!-- Admin Notice -->
				<div class="notice notice-warning is-dismissible">
					<p><span class="dashicons dashicons-warning"></span> <?php printf( _x( 'It looks like your theme is overriding the Full Page Search settings, so the checkbox may not be visible. You can still define a search menu item, by adding a custom link to your menu and assigning the class %s to it.', 'CSS Class Name', 'full-page-search' ), wp_kses_post( '<code>yikes-full-page-search-toggle</code>' ) ); ?></p>
				</div>
			<?php
			}
		}
	}

	/**
	 * AJAX handler to disable the admin notice, when the user dismisses it
	 * @since 1.0.0
	 * @return boolean true/false
	 */
	function dismiss_and_disable_yikes_full_page_search_notice() {
		update_option( 'dismiss_yikes_fullpage_search_menu_notice', 'true' );
		// Confirm the option updated properly
		if ( 'true' === get_option( 'dismiss_yikes_fullpage_search_menu_notice', 'true' ) ) {
			echo wp_send_json_success();
		} else {
			// else, send an error response
			echo wp_send_json_error();
		}
		// Send the success response
		wp_die(); // terminate, and return proper response
	}

	/**
	 * Uninstall action hook
	 * Clear out transients, data etc. etc.
	 * @since 1.0.0
	 */
	public static function uninstall_yikes_full_page_search_plugin() {
		// Include our class
		require_once YIKES_FPS_PATH . 'lib/classes/yikes-full-page-search-uninstall.php';
		// Run the uninstaller function
		YIKES_full_page_search_uninstaller::uninstall();
	}
}
$yikes_search = new YIKES_Full_Page_Search();
