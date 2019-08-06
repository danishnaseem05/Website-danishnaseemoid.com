<?php
/**
 * Used to display post author info
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */


if ( ! function_exists( 'wpex_post_author' ) ) {

	function wpex_post_author() {

		// Only display for standard posts
		global $post;
		$post_id = $post->ID;
		if ( 'post' !== get_post_type($post_id) ) return;

		// Vars
		$author = get_the_author();
		$author_description = get_the_author_meta( 'description' );
		$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
		$author_avatar = get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wpex_author_bio_avatar_size', 75 ) );

		// Only display if author has a description
		if ( !$author_description ) return; ?>

		<div class="author-info boxed clr">
			<h4 class="heading"><span><?php printf( __( 'Written by %s', 'wpex' ), $author ); ?></span></h4>
			<div class="author-info-inner clr">
				<?php if ( $author_avatar ) { ?>
					<a href="<?php echo $author_url; ?>" rel="author" class="author-avatar">
						<?php echo $author_avatar; ?>
					</a>
				<?php } ?>
				<div class="author-description">
					<p><?php echo $author_description; ?></p>
				</div><!-- .author-description -->
			</div><!-- .author-info-inner -->
		</div><!-- .author-info -->

	<?php } // End function

} // End if