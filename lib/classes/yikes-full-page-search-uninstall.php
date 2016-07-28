<?php
/**
 * Main plugin uninstaller class
 * @since 1.0.0
 */

// 	If accessed directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define our uninstaller class
 */
class YIKES_full_page_search_uninstaller {
	public static function uninstall() {
		// Delete our dismissible notice option
		delete_option( 'dismiss_yikes_full_page_search_menu_notice' );
	}
}
?>
