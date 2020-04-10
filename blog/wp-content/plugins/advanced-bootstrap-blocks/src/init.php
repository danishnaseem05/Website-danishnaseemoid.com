<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package advanced-bootstrap-blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function advanced_bootstrap_blocks_block_assets() { // phpcs:ignore

	add_filter( 'block_categories', function( $categories, $post ) {
		return array_merge(
				$categories,
				array(
						array(
								'slug'  => 'advanced-bootstrap-blocks',
								'title' => 'Advanced Bootstrap Blocks',
						),
				)
		);
	}, 10, 2 );

	// Register block styles for both frontend + backend.
	// wp_register_style(
	// 	'advanced-bootstrap-blocks-style-css', // Handle.
	// 	plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
	// 	array( 'wp-editor' ), // Dependency to include the CSS after it.
	// 	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	// );

	// Register block editor script for backend.
	wp_register_script(
		'advanced-bootstrap-blocks-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'advanced-bootstrap-blocks-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */	
	register_block_type(
		'advanced-bootstrap-blocks/container', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'advanced-bootstrap-blocks-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'advanced-bootstrap-blocks-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'advanced-bootstrap-blocks-block-editor-css',
			// Allow block to be saved and re-used
			'reusable'			=> true,
		)
	);
	
	add_theme_support( 'align-wide' );
	// add_theme_support( 'align-full' );

	add_action( 'admin_menu', 'reusable_blocks_adminbar_item' );
	function reusable_blocks_adminbar_item() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page( 
			'Reusable Blocks', 
			'Reusable Blocks', 
			'manage_options', 
			'edit.php?post_type=wp_block', 
			'', 
			'dashicons-welcome-widgets-menus', 
			20 
		);
	}
}

// Hook: Block assets.
add_action( 'init', 'advanced_bootstrap_blocks_block_assets' );