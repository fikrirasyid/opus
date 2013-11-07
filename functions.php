<?php
/**
 * Enqueue scripts and styles
 */
function opus_scripts(){
	wp_enqueue_style( 'opus_google_fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700|Montserrat:300,400,700' );
	wp_enqueue_style( 'opus_style', get_template_directory_uri() . '/css/screen.css' );

	wp_enqueue_script( 'livejs', get_template_directory_uri() . '/js/live.js', array(), '20131106', true );
}
add_action( 'wp_enqueue_scripts', 'opus_scripts' );