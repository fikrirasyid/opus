<?php
/**
 * Setup content width
 * For the time being, most content adjustment is done through js
 * However, theme check requires me to have this variable
 * So here it is, for the time being, for the sake of theme check
 * I have the value correct, tho
 */
if ( ! isset( $content_width ) )
    $content_width = 520;    

class Opus{

    function __construct(){
        $this->import();

        add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
        add_action( 'widgets_init', array( $this, 'widget_setup' ) ); 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );   
        add_filter( 'post_thumbnail_html', array( $this, 'remove_width_attribute' ), 10 );
        add_filter( 'image_send_to_editor', array( $this, 'remove_width_attribute'), 10 );
        add_filter( 'wp_title', array( $this, 'title_tag' ) );
    }

    /**
     * Including / Requiring external files
     * 
     * @return void
     */
    function import(){
        require_once get_template_directory() . '/inc/template-tags.php';
        require_once get_template_directory() . '/inc/customizer.php';        
    }

    /**
     * Setup theme
     * 
     * @return void
     */
    function theme_setup(){

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
        add_theme_support( 'infinite-scroll', array(
            'container' => 'content',
            'footer'    => 'page',
        ) );

        /**
         * Register menu location
         */
        register_nav_menus( array(
            'top_nav' => __( 'Top Navigation', 'opus' )
        ) );

        /**
         * Content editor styling
         */
        add_editor_style( 'css/editor-style.css' );        
    }

    /**
     * Setup widget area
     * 
     * @return void
     */
    function widget_setup(){
        /**
        * Register widget area
        */
        register_sidebar( array(
            'name'          => __( 'Footer Widgets', 'opus' ),
            'id'            => 'footer-widgets',
            'description'   => __( 'Widgets in this area will be shown on Footer Area', 'opus' ),
        ) );
    }

    /**
     * Enqueue scripts and styles
     * 
     * @return void
     */
    function enqueue_scripts_styles(){
        wp_enqueue_style( 'opus_google_fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700|Montserrat:300,400,700' );
        
        wp_enqueue_style( 'opus_style', get_template_directory_uri() . '/css/screen.css' );

        wp_enqueue_script( 'opus_script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '20131106', true );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        
            wp_enqueue_script( 'comment-reply' );
        
        }   
    }

    /**
     * Prints blog name | blog description in wp_title on homepage
     * 
     * @param string title tag
     * 
     * @return string modified title tag
     */
    function title_tag( $title ){
        global $paged, $page;

        $blog_name = get_bloginfo( 'name' );
        $blog_desc = get_bloginfo( 'description' );

        if ( is_home() || is_front_page() ){
            $title = $blog_name;

            if( $paged >= 2 || $page >= 2 ){
                $title .= ' | ' . sprintf( __( 'Page %s', 'opus' ), max( $paged, $page ) );
            } else {
                $title .= ' | ' . get_bloginfo( 'description' );
            }
        }

        return $title;
    }    

    /**
     * Removing widht and height attribute from images
     * 
     * @param string of html sent to editor
     * 
     * @return modified string of html sent to editor
     */
    function remove_width_attribute( $html ) {
       $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
       return $html;
    }    
}
new Opus;