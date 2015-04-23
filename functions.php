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

        add_action( 'after_setup_theme',        array( $this, 'theme_setup' ) );
        add_action( 'widgets_init',             array( $this, 'widget_setup' ) ); 
        add_action( 'wp_enqueue_scripts',       array( $this, 'enqueue_scripts_styles' ) );   
        add_action( 'admin_enqueue_scripts',    array( $this, 'enqueue_admin_scripts_styles' ) );   
        add_filter( 'post_thumbnail_html',      array( $this, 'remove_width_attribute' ), 10 );
        add_filter( 'image_send_to_editor',     array( $this, 'remove_width_attribute'), 10 );
        add_filter( 'wp_title',                 array( $this, 'title_tag' ) );

        /**
         * Fallback for WordPress version lower than 4.1
         */
        if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ){
            add_filter( 'wp_title',     array( $this, 'wp_title'), 10, 2 );
            add_action( 'wp_head',      array( $this, 'render_title' ) );            
        }
    }

    /**
     * Including / Requiring external files
     * 
     * @return void
     */
    function import(){
        require_once get_template_directory() . '/inc/template-tags.php';
        require_once get_template_directory() . '/inc/jetpack.php';
        require_once get_template_directory() . '/inc/customizer.php';  
        require_once get_template_directory() . '/inc/feed.php';  

        new Opus_Customizer;      
        new Opus_Feed;
    }

    /**
     * Get pluggable-by-translation Google Fonts' URL
     */
    function google_fonts_url(){
        $fonts_url = '';
 
        /* Translators: If there are characters in your language that are not
        * supported by Lato, translate this to 'off'. Do not translate
        * into your own language.
        */
        $roboto     = _x( 'on', 'Roboto font: on or off', 'opus' );
        $montserrat = _x( 'on', 'Montserrat font: on or off', 'opus' );

        $font_families = array();

        if( 'off' != $roboto ){
            $font_families[] = 'Roboto:regular,bold,italic,thin,thinitalic,bolditalic';
        }

        if( 'off' != $montserrat ){
            $font_families[] = 'Montserrat:300,400,700';
        }

        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

        return $fonts_url;        
    }

    /**
     * Setup theme
     * 
     * @return void
     */
    function theme_setup(){

        /**
         * Make the theme available for translation
         * The translation is available at /languages/ directory.
         */
        load_theme_textdomain( 'opus', get_template_directory() . '/languages' );

        /**
         * Add theme supports
         */
        $default_custom_header = array(
            'uploads' => true,
            'default-image' => get_template_directory_uri() . '/images/default/three-men.jpg',
            'flex-width' => true,
            'width' => 1140,
            'flex-height' => true,
            'height' => 900
        );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
        add_theme_support( 'custom-header', $default_custom_header );
        add_theme_support( 'custom-background' );

        /**
         * Register menu location
         */
        register_nav_menus( array(
            'top_nav' => __( 'Top Navigation', 'opus' )
        ) );

        /**
         * Content editor styling
         */
        add_editor_style( array(
            $this->google_fonts_url(),
            'css/editor-style.css'
        ) );        

        /**
         * Register image sizes
         */
        add_image_size( 'page-cover', 1140, 900  );
        add_image_size( 'featured', 580, 0  );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );
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
        wp_enqueue_style( 'opus_google_fonts', $this->google_fonts_url() );
        
        wp_enqueue_style( 'opus_style', get_template_directory_uri() . '/css/screen.css' );

        // Adding RTL style
        if( is_rtl() ){
            
            wp_enqueue_style( 'opus_style_rtl', get_template_directory_uri() . '/css/rtl.css', array( 'opus_style' ) );

        }

        wp_enqueue_script( 'opus_script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '20131106', true );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        
            wp_enqueue_script( 'comment-reply' );
        
        }   
    }

    /**
     * Enqueue scripts and styles on admin page
     * 
     * @return void
     */
    function enqueue_admin_scripts_styles(){
        global $pagenow;

        // Add custom stylesheet for edit header screen
        if( 'themes.php' == $pagenow && isset( $_GET['page'] ) && 'custom-header' == $_GET['page'] ){

            wp_enqueue_style( 'opus_google_fonts', '//fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,thinitalic,bolditalic|Montserrat:300,400,700' );

            wp_enqueue_style( 'opus_dashboard_header', get_template_directory_uri() . '/css/dashboard-header.css' );

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
        return opus_remove_width_attribute( $html );
    }    

    /**
     * Filters wp_title to print a neat <title> tag based on what is being viewed.
     * 
     * @param string $title Default title text for current view.
     * @param string $sep Optional separator.
     * @return string The filtered title.
     */
    public function wp_title( $title, $sep ) {
        if ( is_feed() ) {
            return $title;
        }

        global $page, $paged;

        // Add the blog name
        $title .= get_bloginfo( 'name', 'display' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title .= " $sep $site_description";
        }

        // Add a page number if necessary:
        if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
            $title .= " $sep " . sprintf( __( 'Page %s', 'opus' ), max( $paged, $page ) );
        }

        return $title;
    }    

    /**
     * Title shim for sites older than WordPress 4.1.
     *
     * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function render_title() {
        ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <?php
    }    
}
new Opus;