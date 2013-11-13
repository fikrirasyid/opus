<?php 
	get_header();
	while( have_posts() ) : the_post();
?>


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
					<?php if( get_post_format() === false ): ?>
					<h1 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h1>
					<?php endif;?>

					<?php 
						$entry_custom_meta = get_post_custom( get_the_ID()); 
						if( get_post_format() == 'link' && isset( $entry_custom_meta['_format_link_url'] ) ):
					?>
					<h1 class="entry-original-link">
						<?php printf( __( '<a href="%1$s" rel="bookmark">A link to %2$s</a>', 'opus' ), $entry_custom_meta['_format_link_url'][0], opus_get_domain_name( $entry_custom_meta['_format_link_url'][0] ) ); ?>
					</h1>
					<?php endif; ?>					
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