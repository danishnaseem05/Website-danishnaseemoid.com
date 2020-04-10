<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo('name'); ?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php if ( get_theme_mod('wpex_custom_favicon') ) { ?>
		<link rel="shortcut icon" href="<?php echo get_theme_mod('wpex_custom_favicon'); ?>" />
	<?php } ?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php if ( '1' == get_theme_mod( 'wpex_nav', '1' ) ) { ?>
		<div id="site-navigation-wrap">
			<div id="sidr-close"><a href="#sidr-close" class="toggle-sidr-close"></a></div>
			<nav id="site-navigation" class="navigation main-navigation clr container" role="navigation">
				<a href="#sidr-main" id="navigation-toggle"><span class="fa fa-bars"></span><?php echo __( 'Menu', 'wpex' ); ?></a>
				<?php
				// Display main menu
				wp_nav_menu( array(
					'theme_location'	=> 'main_menu',
					'sort_column'		=> 'menu_order',
					'menu_class'		=> 'dropdown-menu sf-menu',
					'fallback_cb'		=> false
				) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- #site-navigation-wrap -->
	<?php } ?>

	<div id="wrap" class="clr">

		<div id="header-wrap" class="clr">
			<header id="header" class="site-header clr container" role="banner">
				<?php
				// Outputs the site logo
				// See functions/logo.php
				wpex_logo();
				// Social links
				wpex_header_aside(); ?>
			</header><!-- #header -->
		</div><!-- #header-wrap -->
		
		<div id="main" class="site-main clr container">