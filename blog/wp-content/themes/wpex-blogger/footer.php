<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */
?>

	</div><!-- #main-content -->
</div><!-- #wrap -->

<footer id="footer-wrap" class="site-footer clr">
	<div id="footer" class="container clr">
		<div id="footer-widgets" class="clr">
			<div class="footer-box span_1_of_3 col col-1">
				<?php dynamic_sidebar( 'footer-one' ); ?>
			</div><!-- .footer-box -->
			<div class="footer-box span_1_of_3 col col-2">
				<?php dynamic_sidebar( 'footer-two' ); ?>
			</div><!-- .footer-box -->
			<div class="footer-box span_1_of_3 col col-3">
				<?php dynamic_sidebar( 'footer-three' ); ?>
			</div><!-- .footer-box -->
		</div><!-- #footer-widgets -->
	</div><!-- #footer -->
</footer><!-- #footer-wrap -->

<div id="copyright" role="contentinfo" class="clr">
	<div class="container clr">
		<?php
		// Displays copyright info
		// See functions/copyright.php
		wpex_copyright(); ?>
		</div><!-- .container -->
</div><!-- #copyright -->

<?php wp_footer(); ?>
</body>
</html>