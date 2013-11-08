<?php get_header(); ?>

	<div id="main" class="site-main">
		<div id="primary" class="content-area">
				<?php 
					while( have_posts() ) : the_post();
						get_template_part( 'content', 'single' );

						opus_content_nav( 'nav-below' ); 
						
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() ){
							comments_template();
						}
					endwhile;					
				?>
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>