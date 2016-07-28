<?php
/**
 * Full Page Search container
 * This gets appended to the WordPress footer via wp_footer action hook
 * Note: This can be overridden by copying into a directory in your theme
 * wp-content/theme/your_theme/yikes-full-page-search/yikes-full-page-search-container.php
 * @since 1.0.0
 */
?>
<!-- Search Container !-->
<div id="search" class="yikes-full-page-search">
	<!-- Modal Close Button -->
	<a href="<?php echo esc_url( home_url() ); ?>" onclick="return false;" type="button" class="close">
		<?php echo apply_filters( 'full_page_search_close_button', '&#10005;' ); ?>
	</a>
	<!-- Search Form -->
	<form role="search" method="get" id="searchform" class="searchform" action="<?php echo apply_filters( 'full_page_search_action', esc_url( home_url( '/' ) ) ); ?>">
		<input type="search" name="s" autocomplete="off" value="<?php echo get_search_query(); ?>" placeholder="<?php echo apply_filters( 'full_page_search_placeholder_text', __( 'type keyword(s) here', 'full-page-search' ) ); ?>" />
		<input type="submit" class="btn btn-primary search-btn" value="<?php echo apply_filters( 'full_page_search_button_text', __( 'Search', 'full-page-search' ) ); ?>">
	</form>
</div>
