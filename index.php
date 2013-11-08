<?php get_header(); ?>

	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="page-cover">
				<img src="<?php echo get_bloginfo('template_directory'); ?>/images/dummy/cover-photo-1.jpg" alt="">
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