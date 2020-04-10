<?php
/**
 * Header aside content
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */


if ( ! function_exists( 'wpex_header_aside()' ) ) {
	function wpex_header_aside() {
		if ( '1' != get_theme_mod( 'wpex_header_aside', '1' ) ) return;
		$social_options = wpex_social_links(); 
		if ( get_theme_mod( 'header-social', '1' && $social_options ) ) { ?>
			<aside id="header-aside" class="clr">
				<?php foreach ( $social_options as $social_option ) {
					$icon = ( 'googleplus' == $social_option ) ? $icon = 'google-plus' : $social_option;
					$name = str_replace('-', ' ', $social_option);
					$name = ucfirst($name); ?>
					<?php if ( '' != get_theme_mod('wpex_social_'. $social_option, '#' ) ) { ?>
						<a href="<?php echo get_theme_mod( 'wpex_social_'. $social_option ); ?>" title="<?php echo $name; ?>"><span class="fa fa-<?php echo $icon; ?>"></span></a>
					<?php } ?>
				<?php } ?>
			</aside>
		<?php } ?>
	<?php } // End function
} // End if