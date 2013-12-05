<?php 
	get_header();
	while( have_posts() ) : the_post();
?>

<header id="header" class="wrap-outer">
	<div class="wrap">
		<h1 id="site-name" class="page-theme"><?php the_title(); ?></h1>
	</div>
</header>


<div id="main" class="site-main">
	<div id="primary" class="content-area">

		<?php if( has_post_thumbnail() ): ?>
		<div id="page-cover">
			<?php the_post_thumbnail(); ?>
		</div>				
		<?php else: ?>
		<div id="page-cover">
			<img src="<?php header_image(); ?>" alt="<?php bloginfo('title'); ?>">
		</div>				
		<?php endif; ?>

		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">					
					<div class="entry-meta single">
						<?php opus_posted_on(); ?>
					</div>
				</header> 
				<div class="entry-content">
					<?php the_content(); ?>
				</div> 
				<footer class="entry-footer">
					<span class="icon-wrap"><span class="icon"></span></span>
					<span class="comments-link">			
						<?php opus_comments_popup_link(); ?>
					</span>
				</footer> 
			</article>

			<?php 
				opus_content_nav( 'nav-below' ); 

				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ){
					comments_template();
				}
			?>
		</div><!-- #content.site-content -->

	</div><!-- #primary.content-area -->
</div><!-- #main.site-main -->
<?php
	endwhile;					
	get_footer(); 
?>