<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div id="primary" class="content-area clr">
		<div id="content" class="site-content left-content clr" role="main">
			<article class="single-post-article boxed clr">
				<?php
				if ( !post_password_required() ) {
					get_template_part( 'content', get_post_format() );
				} ?>
				<header class="page-header clr">
					<h1 class="page-header-title"><?php the_title(); ?></h1>
					<?php
					// Display post meta
					// See functions/commons.php
					wpex_post_meta(); ?>
				</header><!-- .page-header -->
				<div class="entry clr">
					<?php the_content(); ?>
				</div><!-- .entry -->
				<footer class="entry-footer">
					<?php edit_post_link( __( 'Edit Post', 'wpex' ), '<span class="edit-link clr">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article>
			<?php
			// Display author bio
			// See functions/commons.php
			wpex_post_author(); ?>
			<?php comments_template(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		</div><!-- #content -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->
	<?php wpex_next_prev(); ?>
<?php endwhile; ?>
<?php get_footer(); ?>