<?php get_header(); ?>
	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="page-cover">
				<img src="<?php header_image(); ?>" alt="<?php bloginfo('title'); ?>">
			</div>				
			<div id="content" class="site-content" role="main">

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<div class="entry-meta">
						<ul class="entry-category">
							<li><?php _e( 'Error 404', 'opus' ); ?></li>
						</ul>
						
						<div class="entry-posted-on">
							Whoops.
						</div>
					</div>
					
					<h1 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Not Found', 'opus' ); ?></a>
					</h1>
				</header> 
				<div class="entry-content">
					<p><?php _e( 'We\'re sorry, the page you\'re looking for does not exist', 'opus' ); ?></p>
					<p><?php printf( __( 'We suggest you to check our <a href="%1$s" title="Back to homepage">homepage</a> or try to search what you\'re looking for using this search box below:', 'opus' ), home_url() ); ?></p>

					<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url(); ?>">
						<label class="screen-reader-text" for="s">Search for:</label>
						<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Type keywords and hit enter', 'opus' ); ?>" style="margin:0 0 15px;">
						<input type="submit" id="searchsubmit" value="Search" style="margin: 0;">
					</form>					
				</div> 				
				<footer class="entry-footer">
					<span class="icon-wrap"><span class="icon"></span></span>
					<span class="comments-link">			
						<?php _e( 'Commentless', 'opus' ); ?>
					</span>
				</footer> 
			</article>

			</div><!-- #content.site-content -->
		</div><!-- #primary.content-area -->
	</div><!-- #main.site-main -->

<?php get_footer(); ?>