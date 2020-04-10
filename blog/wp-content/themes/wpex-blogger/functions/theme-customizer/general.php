<?php
/**
 * General theme options
 * @package WordPress
 * @subpackage WPTuts WPExplorer Theme
 * @since WPTuts 1.0
 */

function wpex_customizer_general($wp_customize) {

	// Add textarea
	class WPEX_Customize_Textarea_Control extends WP_Customize_Control {
		
		//Type of customize control being rendered.
		public $type = 'textarea';

		//Displays the textarea on the customize screen.
		public function render_content() { ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
			</label>
		<?php
		}
	}

	// Theme Settings Section
	$wp_customize->add_section( 'wpex_general' , array(
		'title'		=> __( 'Theme Settings', 'wpex' ),
		'priority'	=> 200,
	) );

	// Logo Image
	$wp_customize->add_setting( 'wpex_logo', array(
		'type'	=> 'theme_mod',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wpex_logo', array(
		'label'		=> __( 'Image Logo', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_logo',
		'priority'	=> '1',
	) ) );

	// Enable/Disable Navigation
	$wp_customize->add_setting( 'wpex_nav', array(
		'type'		=> 'theme_mod',
		'default'	=> '1',
	) );

	$wp_customize->add_control( 'wpex_nav', array(
		'label'		=> __( 'Top Navigation', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_nav',
		'type'		=> 'checkbox',
		'priority'	=> '3',
	) );

	// Enable/Disable Social
	$wp_customize->add_setting( 'wpex_header_aside', array(
		'type'		=> 'theme_mod',
		'default'	=> '1',
	) );

	$wp_customize->add_control( 'wpex_header_aside', array(
		'label'		=> __( 'Social Links', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_header_aside',
		'type'		=> 'checkbox',
		'priority'	=> '4',
	) );

	// Enable/Disable Readmore
	$wp_customize->add_setting( 'wpex_blog_readmore', array(
		'type'		=> 'theme_mod',
		'default'	=> '1',
	) );

	$wp_customize->add_control( 'wpex_blog_readmore', array(
		'label'		=> __( 'Read More Link', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_blog_readmore',
		'type'		=> 'checkbox',
		'priority'	=> '5',
	) );

	// Enable/Disable Featured Images on Posts
	$wp_customize->add_setting( 'wpex_blog_post_thumb', array(
		'type'		=> 'theme_mod',
		'default'	=> '1',
	) );

	$wp_customize->add_control( 'wpex_blog_post_thumb', array(
		'label'		=> __( 'Featured Image on Posts', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_blog_post_thumb',
		'type'		=> 'checkbox',
		'priority'	=> '6',
	) );

	// Enable/Disable Featured Images on Posts
	$wp_customize->add_setting( 'wpex_infinite_scroll', array(
		'type'		=> 'theme_mod',
		'default'	=> '1',
	) );

	$wp_customize->add_control( 'wpex_infinite_scroll', array(
		'label'		=> __( 'Infinite Scroll', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_infinite_scroll',
		'type'		=> 'checkbox',
		'priority'	=> '7',
	) );

	// Display Excerpts
	$wp_customize->add_setting( 'wpex_entry_content_excerpt', array(
		'type'		=> 'theme_mod',
		'default'	=> 'excerpt',
	) );

	$wp_customize->add_control( 'wpex_entry_content_excerpt', array(
		'label'		=> __( 'Entries Display?', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_entry_content_excerpt',
		'type'		=> 'select',
		'choices'	=> array(
			'excerpt' => __( 'The Excerpt', 'wpex' ),
			'content' => __( 'The Content', 'wpex' ),
		),
		'priority'	=> '8',
	) );

	// Excerpt lengths
	$wp_customize->add_setting( 'wpex_excerpt_length', array(
		'type'		=> 'theme_mod',
		'default'	=> '50',
	) );

	$wp_customize->add_control( 'wpex_excerpt_length', array(
		'label'		=> __( 'Excerpt Length', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_excerpt_length',
		'type'		=> 'text',
		'priority'	=> '9',
	) );

	// Copyright
	$wp_customize->add_setting( 'wpex_copyright', array(
		'type'		=> 'theme_mod',
		'default'	=> 'Powered by <a href=\"http://www.wordpress.org\" title="WordPress" target="_blank">WordPress</a> and <a href=\"http://themeforest.net/user/WPExplorer?ref=WPExplorer" target="_blank" title="WPExplorer" rel="nofollow">WPExplorer Themes</a>',
	) );

	$wp_customize->add_control( new WPEX_Customize_Textarea_Control( $wp_customize, 'wpex_copyright', array(
		'label'		=> __( 'Custom Copyright', 'wpex' ),
		'section'	=> 'wpex_general',
		'settings'	=> 'wpex_copyright',
		'type'		=> 'textarea',
		'priority'	=> '10',
	) ) );

	// Theme Settings Section
	$wp_customize->add_section( 'wpex_social' , array(
		'title'		=> __( 'Social Options', 'wpex' ),
		'priority'	=> 201,
	) );

	// Social Options
	$social_options = wpex_social_links();
	$count=0;
	foreach ( $social_options as $social_option ) {
		$count++;
		$name = $social_option = str_replace('_', ' ', $social_option);
		$name = ucfirst($name);
		$wp_customize->add_setting( 'wpex_social_'. $social_option, array(
			'type'		=> 'theme_mod',
			'default'	=> '#',
		) );
		$wp_customize->add_control( 'wpex_social_'. $social_option, array(
			'label'		=> __( $name , 'wpex' ),
			'section'	=> 'wpex_social',
			'settings'	=> 'wpex_social_'. $social_option,
			'type'		=> 'text',
			'priority'	=> $count,
		) );
	}		
}
add_action( 'customize_register', 'wpex_customizer_general' );