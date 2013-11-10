<?php get_header(); ?>

	<div id="main" class="site-main">
		<div id="primary" class="content-area">
				<?php 
					while( have_posts() ) : the_post();
						get_template_part( 'content', 'single' );					
					endwhile;					
				?>
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>