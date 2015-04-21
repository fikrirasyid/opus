<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Opus
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function opus_jetpack_setup() {
	// Adding infinite scroll support
    add_theme_support( 'infinite-scroll', array(
        'container' => 'content',
        'footer'    => false,
    ) );


	// Adding site logo support
	add_image_size( 'site-logo', 0, 61 );

	$site_logo_args = array(
	    'header-text' => array(
	        'site-title'
	    ),
	    'size' => 'site-logo',
	);

	add_theme_support( 'site-logo', $site_logo_args );	
}
add_action( 'after_setup_theme', 'opus_jetpack_setup' );