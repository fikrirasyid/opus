<article id="post-0" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<ul class="entry-category">
				<li><?php _e( 'Not Found', 'opus' ); ?></li>
			</ul>
			
			<div class="entry-posted-on">
				Whoops.
			</div>
		</div>
		

	</header> 
	<div class="entry-content">
		
		<?php if( is_search() ): ?>
			<?php printf( __( 'There is no article that relates with keyword <strong>%s</strong>', 'opus' ), $s ); ?>
		<?php else: ?>
			<p><?php _e( 'We are sorry, the content you are looking for does not exist', 'opus' ); ?></p>
		<?php endif; ?>

		<p style="margin-top: 20px; font-size: .85em;"><?php printf( __( 'We suggest you to check the <a href="%1$s" title="Back to homepage">homepage</a> or try to search what you\'re looking for using this search box below:', 'opus' ), home_url() ); ?></p>

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