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
	}
	/**
 * Add custom fields to $item nav object
 * in order to be used in custom Walker
 * @access      public
 * @since       1.0
 * @return      void
*/
	function yikes_full_page_search_add_custom_nav_fields( $menu_item ) {
		$menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
		return $menu_item;
	}

	/**
	 * Add the appropriate classes (WP 4.1+)
	 * @param  [type] $classes [description]
	 * @param  [type] $item    [description]
	 * @param  [type] $args    [description]
	 * @param  [type] $depth   [description]
	 * @return [type]          [description]
	 */
	public function yikes_full_page_search_filter_handler( $classes, $item, $args, $depth ) {
		if ( isset( $item->set_search_link ) && 1 === absint( $item->set_search_link ) ) {
			$classes[] = 'yikes-full-page-search-toggle';
		}
		return $classes;
	}

	/**
	 * Enqueue the scripts and styles for the search to function
	 * @return null
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
		if ( file_exists( get_stylesheet_directory() . '/yikes-full-page-popup/yikes-full-page-search-container.php' ) ) {
			require_once get_stylesheet_directory() . '/yikes-full-page-popup/yikes-full-page-search-container.php';
		}
		require_once YIKES_FPS_PATH . 'lib/templates/yikes-full-page-search-container.php';
	}
}
$yikes_search = new YIKES_Full_Page_Search();
