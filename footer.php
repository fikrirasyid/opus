	<a href="#" id="colophon-toggle">
		<span class="label"><?php _e( 'More', 'opus' ); ?></span>
		<span class="genericon genericon-collapse"></span>
		<span class="genericon genericon-expand"></span>
	</a>
	<footer id="colophon" class="site-footer wrap-outer" role="contentinfo">
		<div id="secondary" class="widget-area" role="complementary">
			<div class="wrap">
				<aside id="nav_menu-3" class="widget widget_nav_menu">					
					<ul>
						<?php dynamic_sidebar( 'footer-widgets' ); ?>
					</ul>
				</aside>
			</div> 
		</div> 

		<div class="site-info">
			<div class="wrap">
				<?php
					printf(
						__( 'Powered by %1$s and %2$s', 'opus' ),
						'<a href="http://wordpress.org/" title="A Semantic Personal Publishing Platform">WordPress</a>',
						'<a href="http://fikrirasyid.com/wordpress-themes/opus/">Opus Theme</a>'
					);
				?>			
			</div> 
		</div> 
	</footer><!-- #colophon.site-footer -->
	<?php wp_footer(); ?>
</body>
</html>