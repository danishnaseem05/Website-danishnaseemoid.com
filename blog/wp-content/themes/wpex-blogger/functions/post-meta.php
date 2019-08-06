<?php
/**
 * Used to output post meta info
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */


if ( ! function_exists( 'wpex_post_meta' ) ) {
	function wpex_post_meta() { ?>
		<ul class="post-meta clr">
			<li class="meta-date">
				<?php _e( 'Posted on', 'wpex' ); ?> <span class="meta-date-text"><?php echo get_the_date(); ?></span>
			</li>
			<?php if ( 'post' == get_post_type() ) { ?>
				<li class="meta-category">
					<span class="meta-seperator">/</span><?php _e( 'Under', 'wpex' ); ?> <?php the_category( ', ' ); ?>
				</li>
			<?php } ?>
			<?php if( comments_open() ) { ?>
				<li class="meta-comments comment-scroll">
					<span class="meta-seperator">/</span><?php _e( 'With', 'wpex' ); ?> <?php comments_popup_link( __( '0 Comments', 'wpex' ), __( '1 Comment',  'wpex' ), __( '% Comments', 'wpex' ), 'comments-link' ); ?>
				</li>
			<?php } ?>
		</ul><!-- .post-meta -->
		<?php
	}
}