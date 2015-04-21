<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<nav id="top-nav" class="wrap-outer" role="navigation">
		<div class="wrap">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="home" title="<?php printf( __( 'Back to %1$s', 'opus' ), get_bloginfo('name') ); ?>"><?php echo get_avatar( get_bloginfo( 'admin_email' ), 75 ); ?></a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="home-name" title="<?php printf( __( 'Welcome To %1$s', 'opus' ), get_bloginfo('name') ); ?>"><?php echo bloginfo('name'); ?></a>
			<?php
				if( function_exists( 'jetpack_the_site_logo' ) ){
				    jetpack_the_site_logo();
				}
			?>			

			<h2 id="top-nav-toggle" class="menu-toggle" title="Show Menu">
				<span class="label">Show Menu</span>
				<i class="genericon genericon-menu"></i>
				<i class="genericon genericon-close-alt"></i>
			</h2>
			<div class="top-nav-container">
				<?php wp_nav_menu( array( 'theme_location' => 'top_nav' ) ); ?>
			</div>
		</div>
	</nav>
	
	<?php if( !is_singular() && !is_search() && !is_archive() ): ?>
	<header id="header" class="wrap-outer">
		<div class="wrap">
			<?php
				if( function_exists( 'jetpack_the_site_logo' ) ){
				    jetpack_the_site_logo();
				}
			?>
			<h1 id="site-name" class="page-theme"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
			<h2 id="site-desc" class="page-theme-description"><?php bloginfo('description'); ?></h2>			
		</div>
	</header>
	<?php endif; ?>