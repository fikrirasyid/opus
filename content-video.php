<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<?php opus_the_category(); ?>

			<?php opus_posted_on(); ?>
		</div>					
		<?php 
			$video = get_post_meta( get_the_ID(), '_format_video_embed', true );
			if( $video ){
				opus_get_video_embed_code( $video );
			}
		?>
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