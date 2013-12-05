<?php
/**
 * Theme setup
 */
function opus_setup(){
	/**
	 * Add theme supports
	 */
    $default_custom_header = array(
        'uploads' => true,
        'default-image' => get_template_directory_uri() . '/images/default/three-men.jpg',
        'flex-width' => true,
        'width' => 960,
        'flex-height' => true,
        'height' => 720
    );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
    add_theme_support( 'custom-header', $default_custom_header );

	/**
	 * Register menu location
	 */
	register_nav_menus( array(
		'top_nav' => __( 'Top Navigation', 'opus' )
	) );
}
add_action( 'after_setup_theme', 'opus_setup' );

function opus_widget_setup(){
    /**
    * Register widget area
    */
    register_sidebar( array(
        'name' => __( 'Footer Widgets', 'opus' ),
        'id' => 'footer-widgets',
        'description' => __( 'Widgets in this area will be shown on Footer Area', 'opus' ),
    ) );
}
add_action( 'widgets_init', 'opus_widget_setup' );

/**
 * Setup content width
 * For the time being, most content adjustment is done through js
 * However, theme check requires me to have this variable
 * So here it is, for the time being, for the sake of theme check
 * I have the value correct, tho
 */
if ( ! isset( $content_width ) )
    $content_width = 520;

/**
 * Enqueue scripts and styles
 */
function opus_scripts(){
	wp_enqueue_style( 'opus_google_fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700|Montserrat:300,400,700' );
	wp_enqueue_style( 'opus_style', get_template_directory_uri() . '/css/screen.css' );

	wp_enqueue_script( 'opus_script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '20131106', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }    
}
add_action( 'wp_enqueue_scripts', 'opus_scripts' );

/**
 * Content editor styling
 */
function opus_editor_style(){
    add_editor_style( 'css/editor-style.css' );
}
add_action( 'init', 'opus_editor_style' );

/**
 * Removing widht and height attribute from images
 */
add_filter( 'post_thumbnail_html', 'opus_remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'opus_remove_width_attribute', 10 );

function opus_remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Determine if a background image needs light or dark text
 * Source: http://stackoverflow.com/questions/5842440/background-image-dark-or-light
 * get average luminance, by sampling $num_samples times in both x,y directions
 */
function opus_get_avg_luminance($filename, $num_samples=10) {
    $img = imagecreatefromjpeg($filename);

    $width = imagesx($img);
    $height = imagesy($img);

    $x_step = intval($width/$num_samples);
    $y_step = intval($height/$num_samples);

    $total_lum = 0;

    $sample_no = 1;

    for ($x=0; $x<$width; $x+=$x_step) {
        for ($y=0; $y<$height; $y+=$y_step) {

            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // choose a simple luminance formula from here
            // http://stackoverflow.com/questions/596216/formula-to-determine-brightness-of-rgb-color
            $lum = ($r+$r+$b+$g+$g+$g)/6;

            $total_lum += $lum;

            // debugging code
 //           echo "$sample_no - XY: $x,$y = $r, $g, $b = $lum<br />";
            $sample_no++;
        }
    }

    // work out the average
    $avg_lum  = $total_lum/$sample_no;

    return $avg_lum;
}