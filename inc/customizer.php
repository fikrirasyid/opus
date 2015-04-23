<?php
/**
 * Opus Theme Customizer
 *
 * @package Opus
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function opus_customize_register( $wp_customize ) {
	// Remove unused Control
	$wp_customize->remove_control( 'display_header_text' );
	$wp_customize->remove_control( 'header_textcolor' );

	// Add Custom Setting & Control
	$wp_customize->add_setting( 
		'site_color',
		array(
			'default'			=> '#1f7f5c',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'			=> 'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 
			'site_color',
			array(
				'label' 	=> __( 'Site Color', 'opus' ),
				'section' 	=> 'colors',
				'settings' 	=> 'site_color'
			)
		)
	);
}
add_action( 'customize_register', 'opus_customize_register', 20 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function opus_customize_preview_js( $wp_customize ) {
	wp_enqueue_script( 'opus_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150404', true );

	// Default params
	$opus_customizer_params = array(
		'generate_color_scheme_endpoint' 		=> esc_url( admin_url( 'admin-ajax.php?action=opus_generate_customizer_color_scheme' ) ),
		'generate_color_scheme_error_message' 	=> __( 'Error generating color scheme. Please try again.', 'opus' ),
		'clear_customizer_settings'				=> esc_url( admin_url( 'admin-ajax.php?action=opus_clear_customizer_settings' ) )		
	);

	// Adding proper error message when customizer fails to generate color scheme in live preview mode (theme hasn’t been activated). 
	// The color scheme is generated using wp_ajax and wp_ajax cannot be registered if the theme hasn’t been activated.
	if( ! $wp_customize->is_theme_active() ){
		$opus_customizer_params['generate_color_scheme_error_message'] = __( 'Color scheme cannot be generated. Please activate Opus theme first.', 'opus' );
	}

	// Attaching variables
	wp_localize_script( 'opus_customizer', 'opus_customizer_params', apply_filters( 'opus_customizer_params', $opus_customizer_params ) );

	// Display color scheme previewer
	$color_scheme = get_theme_mod( 'site_color_scheme_customizer', false );

	if( $color_scheme ){
		remove_action( 'wp_enqueue_scripts', 'opus_color_scheme' );

		/**
		 * Reload default style, wp_add_inline_style fail when the handle doesn't exist 
		 */
		wp_enqueue_style( 'opus-style', get_template_directory_uri() . '/css/screen.css' );

		$inline_style = wp_add_inline_style( 'opus-style', $color_scheme );
	}	
}
add_action( 'customize_preview_init', 'opus_customize_preview_js' );

/**
 * WordPress' native sanitize_hex_color seems to be hasn't been loaded
 * Provide theme's customizer with its own hex color sanitation
 */
if( ! function_exists( 'opus_sanitize_hex_color' ) ) :
function opus_sanitize_hex_color( $color ){
	if ( '' === $color ){
		return '';	
	}

	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}
endif;

if ( ! function_exists( 'opus_sanitize_hex_color_no_hash' ) ) :
function opus_sanitize_hex_color_no_hash( $color ){
	$color = ltrim( $color, '#' );

	if ( '' === $color )
		return '';

	return opus_sanitize_hex_color( '#' . $color ) ? $color : null;	
}
endif;


/**
 * Generate color scheme based on color given
 * 
 * @uses Opus_Simple_Color_Adjuster
 */
if( ! function_exists( 'opus_generate_color_scheme_css' ) ) :
function opus_generate_color_scheme_css( $color, $mode = false ){
	
	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// Verify color
	if( ! opus_sanitize_hex_color( $color ) ){
		return false;
	}

	$simple_color_adjuster 	= new Opus_Simple_Color_Adjuster;
	$site_color 			= $color;
	$site_color_darker 		= $simple_color_adjuster->darken( $site_color, 20 );

	switch ( $mode ) {
		
		case 'site_color':

			$css = "
/* Main Site Color */
#top-nav,
#top-nav.scrolled,
body.expanded #top-nav,
#content article .entry-header .entry-original-link a:hover,
#content article .entry-footer,
input[type='submit'],
button,
.button,
#comments .pingback.bypostauthor > .comment-wrap .comment-author a:after, 
#comments .comment.bypostauthor > .comment-wrap .comment-author a:after{
	background: {$site_color};
}

a,
#content article .entry-header .entry-title a:hover,
.entry-content h2,
.entry-content a,
#colophon a:hover,
#wp-calendar tbody tr td a,
#content article .entry-meta .entry-category a:hover{
	color: {$site_color};			
}

/* Slightly Darker Color From Site Color */
#content article .entry-header .entry-original-link a:active,
input[type='submit']:active,
button:active,
.button:active{
	color: {$site_color_darker};
}

#top-nav .top-nav-container ul li a:hover,
.entry-content a:hover,
#comments .navigation-comment a:active,
#content .article-month,
#content .article-year{
	color: {$site_color_darker};
}

input[type='submit'],
button,
.button{
	border-bottom-color: {$site_color_darker};
}
			";

			break;

		default:

			$css = false;

			break;
	}

	return $css;
}
endif;

/**
 * AJAX endpoint for generating color scheme in near real time for customizer
 */
if( ! function_exists( 'opus_generate_customizer_color_scheme' ) ) :
function opus_generate_customizer_color_scheme(){

	if( current_user_can( 'customize' ) ){

		// Determine color key
		if( isset( $_GET['site_color'] ) && opus_sanitize_hex_color_no_hash( $_GET['site_color'] ) ){
			$prefix = 'site';
		} else {
			$prefix = false;
		}

		// Saving color
		if( $prefix ){

			$key = $prefix . '_color';

			// Get color
			$color = opus_sanitize_hex_color_no_hash( $_GET[ $key ] );

			if( $color ){

				$color = '#' . $color;

				// Generate color scheme css
				$css = opus_generate_color_scheme_css( $color, $key );

				// Set Color Scheme
				set_theme_mod( $key . '_scheme_customizer', $css );

				$generate = array( 'status' => true, 'colorscheme' => $css );

			} else {

				$generate = array( 'status' => false, 'colorscheme' => false );

			}

		} else {

			$generate = array( 'status' => false, 'colorscheme' => false );

		}

	} else {

		$generate = array( 'status' => false, 'colorscheme' => false );

	}

	// Transmit message
	echo json_encode( $generate ); 

	die();
}
endif;
add_action( 'wp_ajax_opus_generate_customizer_color_scheme', 'opus_generate_customizer_color_scheme' );

/**
 * Generate color scheme based on color choosen by user
 */
if ( ! function_exists( 'opus_save_color_scheme' ) ) :
function opus_save_color_scheme(){

	$key = 'site_color';

	$color = get_theme_mod( $key, false );

	if( $color ){

		$css = opus_generate_color_scheme_css( $color, $key );

		if( $css ){

			// Set color scheme
			set_theme_mod( $key . '_scheme', $css );

			// Remove customizer scheme
			remove_theme_mod( $key . '_scheme_customizer' );

		}
	}
}
endif;
add_action( 'customize_save_after', 'opus_save_color_scheme' );

/**
 * Endpoint for clearing all customizer temporary settings
 * This is made to be triggered via JS call (upon tab is closed)
 * 
 * @return void
 */
if( ! function_exists( 'opus_clear_customizer_settings' ) ) :
function opus_clear_customizer_settings(){
	if( current_user_can( 'customize' ) ){
		remove_theme_mod( 'site_color_scheme_customizer' );		
	}

	die();
}
endif;
add_action( 'wp_ajax_opus_clear_customizer_settings', 'opus_clear_customizer_settings' );