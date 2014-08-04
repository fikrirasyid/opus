<?php get_header(); ?>
	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="page-cover">
				<img src="<?php header_image(); ?>" alt="<?php bloginfo('title'); ?>">
			</div>				
			<div id="content" class="site-content" role="main">

			<?php get_template_part( 'content', 'none' ); ?>

			</div><!-- #content.site-content -->
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>