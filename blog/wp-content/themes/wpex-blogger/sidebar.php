<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */

if ( is_active_sidebar( 'sidebar' ) ) : ?>
	<aside id="secondary" class="sidebar-container" role="complementary">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar' ); ?>
			</div>
		</div>
	</aside><!-- #secondary -->
<?php endif; ?>