<?php get_header(); ?>
	<header id="header" class="wrap-outer">
		<div class="wrap">
			<h1 id="site-name" class="page-theme"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php _e( 'Search Results', 'opus' ); ?></a></h1>
			<h2 id="site-desc" class="page-theme-description"><?php printf( 'Posts related with "<strong>%1$s</strong>"', esc_html( $s ) ); ?></h2>			
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