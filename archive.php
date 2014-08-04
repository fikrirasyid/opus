<?php 
	get_header(); 

	if( is_category() ){
		$page_theme = single_cat_title( '', false );
		$page_theme_desc = category_description();
	} elseif( is_tag() ){
		$page_theme = single_tag_title( '', false );
		$page_theme_desc = tag_description();
	} elseif( is_author() ){
		$page_theme = get_the_author();
		$page_theme_desc = get_the_author_meta( 'description' );
	} elseif( is_day() ){
		$page_theme = get_the_date();
		$page_theme_desc = sprintf( __( 'Posts published at %1$s', 'opus' ), get_the_date() );
	} elseif( is_month() ){
		$page_theme = get_the_date( 'F Y' );
		$page_theme_desc = sprintf( __( 'Posts published on %1$s', 'opus' ), get_the_date( 'F Y' ) );
	} elseif( is_year() ){
		$page_theme = get_the_date( 'Y' );
		$page_theme_desc = sprintf( __( 'Posts published on the year %1$s', 'opus' ), get_the_date( 'Y' ) );
	} elseif( is_tax( 'post_format', 'post-format-image' ) ){
		$page_theme = __( 'Photos', 'opus' );
		$page_theme_desc = sprintf( __( 'Photos published on %1$s', 'opus' ), get_bloginfo('name') );
	} elseif( is_tax( 'post_format', 'post-format-video' ) ){
		$page_theme = __( 'Videos', 'opus' );
		$page_theme_desc = sprintf( __( 'Videos published on %1$s', 'opus' ), get_bloginfo('name') );
	} elseif( is_tax( 'post_format', 'post-format-quote' ) ){
		$page_theme = __( 'Quotes', 'opus' );
		$page_theme_desc = sprintf( __( 'Quotes quoted on %1$s', 'opus' ), get_bloginfo('name') );
	} elseif( is_tax( 'post_format', 'post-format-link' ) ){
		$page_theme = __( 'Links', 'opus' );
		$page_theme_desc = sprintf( __( 'Links shared on %1$s', 'opus' ), get_bloginfo('name') );
	} elseif( is_tax( 'mention' ) ){
		$page_theme = __( 'Mention', 'opus' );
		$page_theme_desc = sprintf( __( 'Posts mentioning %1$s', 'opus' ), opus_get_current_tax( 'mention' ) );		
	} else {
		$page_theme = __( 'Archive', 'opus' );		
		$page_theme_desc = '';
	}
?>
	<header id="header" class="wrap-outer">
		<div class="wrap">
			<h1 id="site-name" class="page-theme"><?php echo $page_theme; ?></h1>
			<h2 id="site-desc" class="page-theme-description"><?php echo $page_theme_desc; ?></h2>			
		</div>
	</header>

	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="page-cover">
				<img src="<?php header_image(); ?>" alt="<?php bloginfo('title'); ?>">
			</div>				
			<div id="content" class="site-content" role="main">
				<?php 
					if( have_posts() ){
						while( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;

						opus_content_nav( 'nav-below' ); 

					} else {
						get_template_part( 'content', 'none' );
					}
				?>
			</div><!-- #content.site-content -->
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>