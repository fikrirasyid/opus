<?php get_template_part( 'article', 'day' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta darker">
			<?php opus_the_category(); ?>

			<?php opus_posted_on(); ?>
		</div>
		
		<?php if( has_post_thumbnail() ): ?>
		<div class="entry-background">
			<div class="shadow"></div>
			<?php the_post_thumbnail( 'featured' ); ?>
		</div>
		<?php endif; ?>
	</header> 
	<div class="entry-content">
		<?php the_content( __( 'Continue Reading &rarr;', 'opus' ) ); ?>
	</div> 
	<footer class="entry-footer">
		<span class="icon-wrap"><span class="icon"></span></span>
		<span class="comments-link">
			<?php opus_comments_popup_link(); ?>
		</span>
	</footer> 
</article><!-- article -->