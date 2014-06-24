<?php get_header(); ?>

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

						if( !current_theme_supports('infinite-scroll') )
							opus_content_nav( 'nav-below' ); 

					} else {
						get_template_part( 'content', 'none' );
					}
				?>
			</div><!-- #content.site-content -->
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>