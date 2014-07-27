<?php 
	get_header();
	while( have_posts() ) : the_post();

	// Get categories. We only display the first, tho.
	$categories = get_the_category( get_the_ID() );

	// Get the featured image
	if( has_post_thumbnail() ){		
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), $size = 'page-cover' );

		// Check the luminance of featured image
		if( isset( $featured_image ) && $featured_image != '' ){
			$featured_image_pathinfo = pathinfo( $featured_image[0] );

			if( isset( $featured_image_pathinfo['extension'] ) && in_array( $featured_image_pathinfo['extension'], array( 'jpg', 'jpeg' ) ) ){
				$luminance = opus_get_avg_luminance( $featured_image[0] );				
			} else {
				$luminance = 200;
			}
		} else {
			$luminance = 200;
		}

		// Set color scheme
		if($luminance > 170){
			$color_scheme = 'dark';
		} else {
			$color_scheme = 'light';
		}

		// Check the image orientation
		if($featured_image[1] >= $featured_image[2]){
			// width > height == landscape
			$stretch = 'height:100%';
		} else {
			// portrait
			$stretch = 'width:100%';
		}

		$featured_image_img = "<img src='{$featured_image[0]}' />";
	} else {
		$color_scheme = 'dark';
	}
?>

<?php if( is_attachment() ): ?>

<header id="header" class="wrap-outer single dark">
	<div class="wrap">
		<h1 id="site-name" class="page-theme"><?php _e( 'Attachment', 'opus' ); ?></h1>
		<h2 id="site-desc" class="page-theme-description"><a href="<?php echo home_url(); ?>" title="<?php _e( '&larr; Back to post', 'opus' ); ?>"><?php _e( '&larr; Back to post', 'opus' ); ?></a></h2>			
	</div>
</header>

<?php else : ?>

<header id="header" class="wrap-outer single <?php echo $color_scheme; ?>">
	<div class="wrap">
		<h1 id="site-name" class="page-theme"><a href="<?php echo esc_url( get_category_link( $categories[0]->cat_ID ) ); ?>" title="<?php printf( __( 'View all %1$s Posts', 'opus' ), $categories[0]->name ); ?>" rel="home"><?php echo $categories[0]->name; ?></a></h1>
		<h2 id="site-desc" class="page-theme-description"><?php echo $categories[0]->description; ?></h2>			
	</div>
</header>

<?php endif; ?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">

		<?php if( has_post_thumbnail() ): ?>
		<div id="page-cover">
			<?php echo $featured_image_img; ?>
		</div>				
		<?php endif; ?>

		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<div class="entry-meta single">
						<?php opus_posted_on(); ?>
					</div>
					
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
						<?php printf( __( '<a href="%1$s" rel="bookmark">link to %2$s</a>', 'opus' ), $entry_custom_meta['_format_link_url'][0], opus_get_domain_name( $entry_custom_meta['_format_link_url'][0] ) ); ?>
					</h1>
					<?php endif; ?>					
				</header> 
				<div class="entry-content">
					<?php 
						// Consciously Prepend video for post video format
						$post_format = get_post_format( get_the_ID() );

						if( 'video' == $post_format ){
							$video  = get_post_meta( get_the_ID(), '_format_video_embed', true );

							if( $video ){
								opus_get_video_embed_code( $video );
							}
						}

						// Display content
						the_content(); 

						// Display page link
						wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'opus' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); 

						// Display tags
						opus_the_tags();
					?>										
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