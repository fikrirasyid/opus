<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Opus</title>

	<!-- <link rel='stylesheet' href='http://10.0.1.212/fikrirasyid/wp-content/themes/opus/css/screen.css' type='text/css' media='all'/> -->
	<?php wp_head(); ?>
</head>
<body>
	<nav id="top-nav" class="wrap-outer" role="navigation">
		<div class="wrap">
			<h2 id="top-nav-toggle" class="menu-toggle" title="Show Menu">Show Menu</h2>
			<div class="top-nav-container">
				<?php wp_nav_menu( array( 'theme_location' => 'top_nav' ) ); ?>
			</div>
		</div>
	</nav>
	
	<?php if( !is_single() ): ?>
	<header id="header" class="wrap-outer">
		<div class="wrap">
			<h1 id="site-name" class="page-theme"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
			<h2 id="site-desc" class="page-theme-description"><?php bloginfo('description'); ?></h2>			
		</div>
	</header>
	<?php endif; ?>