<?php
/**
 * Full Page Search container
 * This gets appended to the WordPress footer via wp_footer action hook
 * @since 1.0.0
 */
?>
<!-- Search Container !-->
<div id="search" class="yikes-full-page-search">
	<button type="button" class="close">×</button>
	<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" name="s" autocomplete="off" value="<?php echo get_search_query(); ?>" placeholder="type keyword(s) here" />
		<button type="submit" class="btn btn-primary search-btn">Search</button>
	</form>
</div>
