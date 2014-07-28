<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Customizer related adjustment
 */
class Opus_Customizer{
	function __construct(){
		add_action( 'customize_register', array( $this, 'register_customizer' ) );
		add_action( 'wp_head', array( $this, 'customize_css' ) );
	}

	/**
	 * Register customizer
	 * 
	 * @return void
	 */
	function register_customizer( $wp_customize ){
		// Remove unused Control
		$wp_customize->remove_control( 'display_header_text' );
		$wp_customize->remove_control( 'header_textcolor' );

		// Add Custom Setting & Control
		$wp_customize->add_setting( 
			'site_color',
			array(
				'default' => '#1f7f5c',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 
				'link_color',
				array(
					'label' 	=> __( 'Site Color', 'opus' ),
					'section' 	=> 'colors',
					'settings' 	=> 'site_color'
				)
			)
		);
	}

	/**
	 * Customize CSS
	 * 
	 * @return void
	 */
	function customize_css(){
		$site_color = get_theme_mod( 'site_color' );
		$site_color_darker = $this->adjust_brightness( get_theme_mod( 'site_color' ), -50 );

		?>
		<style type="text/css">
			/* Main Site Color */
			#top-nav,
			#top-nav.scrolled,
			body.expanded #top-nav,
			#content article .entry-header .entry-original-link a:hover,
			#content article .entry-footer,
			input[type="submit"],
			button,
			.button,
			#comments .pingback.bypostauthor > .comment-wrap .comment-author a:after, 
			#comments .comment.bypostauthor > .comment-wrap .comment-author a:after
			{
				background: <?php echo $site_color; ?>;
			}

			a,
			#content article .entry-header .entry-title a:hover,
			.entry-content h2,
			.entry-content a,
			#colophon a:hover,
			#wp-calendar tbody tr td a{
				color: <?php echo $site_color; ?>;			
			}

			/* Slightly Darker Color From Site Color */
			#content article .entry-header .entry-original-link a:active,
			input[type="submit"]:active,
			button:active,
			.button:active{
				color: <?php echo $site_color_darker; ?>;			
			}

			#top-nav .top-nav-container ul li a:hover,
			.entry-content a:hover,
			#comments .navigation-comment a:active,
			#content .article-month{
				color: <?php echo $site_color_darker; ?>;
			}

			input[type="submit"],
			button,
			.button{
				border-bottom-color: <?php echo $site_color_darker; ?>;
			}
		</style>
		<?php
	}	

	/**
	 * Create slightly darker hexacode color
	 * 
	 * @link http://stackoverflow.com/a/11951022 - Adopted from Torkil Johnsen's Stack Overflow answer
	 * 
	 * @param string hexacode
	 * @param int of steps
	 * 
	 * @return string of hexacode
	 */
	function adjust_brightness($hex, $steps) {
	    // Steps should be between -255 and 255. Negative = darker, positive = lighter
	    $steps = max(-255, min(255, $steps));

	    // Format the hex color string
	    $hex = str_replace('#', '', $hex);
	    if (strlen($hex) == 3) {
	        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	    }

	    // Get decimal values
	    $r = hexdec(substr($hex,0,2));
	    $g = hexdec(substr($hex,2,2));
	    $b = hexdec(substr($hex,4,2));

	    // Adjust number of steps and keep it inside 0 to 255
	    $r = max(0,min(255,$r + $steps));
	    $g = max(0,min(255,$g + $steps));  
	    $b = max(0,min(255,$b + $steps));

	    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
	    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
	    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

	    return '#'.$r_hex.$g_hex.$b_hex;
	}	
}