<?php get_template_part( 'article', 'time' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<?php opus_the_category(); ?>

			<?php opus_posted_on(); ?>
		</div>					
		<?php 
			$entry_custom_meta = get_post_custom( get_the_ID()); 
			if( isset( $entry_custom_meta['_format_link_url'] ) ):
		?>
		<h1 class="entry-original-link">
			<?php printf( __( '<a href="%1$s" rel="bookmark">link to %2$s</a>', 'opus' ), $entry_custom_meta['_format_link_url'][0], opus_get_domain_name( $entry_custom_meta['_format_link_url'][0] ) ); ?>
		</h1>
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
</article>