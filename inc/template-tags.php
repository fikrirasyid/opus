<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Prints HTML of categories of current post
 * 
 * @return void
 */
function opus_the_category(){
	echo '<ul class="entry-category"><li>';
	the_category( '</li><li>', 'single' );
	echo '</li></ul>';
}

/**
 * Prints HTML of tags of current post
 * 
 * @return string
 */
function opus_the_tags(){
	$tags = get_the_tag_list( __( 'Tags: ', 'opus' ), ', ', '' );
	if( $tags ){
		echo '<div class="entry-tags">';
		echo $tags;
		echo '</div>';
	}
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 * 
 * @return void
 */
function opus_posted_on() {
	if( is_page() ){
		printf(
			__( '
				<div class="entry-posted-on">
					<span class="entry-author author vcard"><a href="%1$s" class="url fn n" title="%2$s" rel="author">%3$s</a> </span> 
					<span class="entry-author-action">updated this</span> 
					<a href="%4$s" title="%5$s" class="entry-date">
						<span class="human-time on-load">%6$s</span>
						<span class="conventional-time on-hover">in %7$s</span>
					</a>
				</div>
			', 'opus' ),
			esc_url( get_permalink() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author(),
			esc_url( get_permalink() ),
			sanitize_text_field( get_the_modified_date() ),
			esc_html( opus_get_the_human_time( strtotime( get_the_modified_date('c') ) ) ),
			esc_html( get_the_modified_date() )
		);
	} elseif( is_single() ) {
		printf(
			__( '
				<div class="entry-posted-on">
					<span class="entry-author author vcard"><a href="%1$s" class="url fn n" title="%2$s" rel="author">%3$s</a> </span> 
					<span class="entry-author-action">published this</span> 
					<a href="%4$s" title="%5$s" class="entry-date">
						<span class="human-time on-load">%6$s</span>
						<span class="conventional-time on-hover">in %7$s</span>
					</a>
				</div>
			', 'opus' ),
			esc_url( get_permalink() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author(),
			esc_url( get_permalink() ),
			sanitize_text_field( get_the_title() ),
			esc_html( opus_get_the_human_time( strtotime( get_the_date('c') ) ) ),
			esc_html( get_the_date() )
		);
	} else {
		printf(
			__( '
				<div class="entry-posted-on">
					<a href="%1$s" title="%2$s" class="entry-date">
						<span class="on-load">%3$s</span>
						<span class="on-hover">
							%4$s by <strong>%5$s</strong>
						</span>
					</a>
				</div>
			', 'opus' ),
			esc_url( get_permalink() ),
			sanitize_text_field( get_the_title() ),
			esc_html( get_the_date( 'H:i' ) ),
			esc_html( get_the_date( 'F j, Y H:i' ) ),
			get_the_author()
		);
	}
}

/**
 * Returns human readable time
 * 
 * @param int post timestamp
 * 
 * @return string human time
 */
function opus_get_the_human_time($post_time = 0){

	$timestamp = current_time( 'timestamp' );

	$time_diff = human_time_diff( $post_time, $timestamp );

	if( $post_time < $timestamp ){
		// Past
		$human_time = sprintf( __( '%s ago', 'opus' ), $time_diff );
	} else {
		// Future
		$human_time = sprintf( __( '%s from now', 'opus' ), $time_diff );
	}

	return $human_time;
}

/**
 * Returns domain name of given URL
 * 
 * @param string url
 * 
 * @return string domain name
 */
function opus_get_domain_name( $url ){
	$parsed_url = parse_url( $url );

	if( !isset( $parsed_url['scheme'] ) ){
		$url = 'http://' . $url;
	}

	return parse_url( $url , PHP_URL_HOST );	
}

/**
 * Prints HTML for comments popup link section. For the sake of DRY
 * 
 * @return void
 */
function opus_comments_popup_link(){
	comments_popup_link( __( 'No Response Yet. <strong>Add Yours</strong>', 'opus' ), __( '1 Response Shared. <strong>Add Yours</strong>', 'opus' ), __( '% Responses Shared. <strong>Add Yours</strong>', 'opus' ) );
}

/**
 * Display navigation to next/previous pages when applicable
 * 
 * @param string navid
 * 
 * @return void
 */
function opus_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'opus' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>
	
		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '', 'Previous post link', 'opus' ) . '</span> <span class="label">Older</span>' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="label">Newer</span> <span class="meta-nav">' . _x( '', 'Next post link', 'opus' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav"></span> Older', 'opus' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav"></span>', 'opus' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}

/**
 * Display the comment item
 * 
 * @return void
 */
function opus_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">	
		<div id="div-comment-<?php comment_ID() ?>" class="comment-wrap">
			<div class="comment-wrap-inside">
				<div class="comment-avatar">
					<span class="comment-tail"></span>
					<?php echo get_avatar($comment, 60, ''); ?>
				</div>
				<div class="comment-content">
					<div class="tail"></div>
					
					<div class="comment-meta">						
						<div class="comment-author"><?php comment_author_link(); ?></div>
					</div>

					<div class="entry-content">
						<?php if ($comment->comment_approved == '0') : ?>
						<p><em><?php _e('comment will appear after being approved by admin.', 'opus') ?></em> </p>
						<?php endif; ?>						
						<?php comment_text() ?>
					</div>
				</div>
				<div class="comment-action">
					<?php comment_reply_link(array_merge( $args, array('reply_text' => __('<span class="label">Reply</span>', 'opus'), 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>

					<div class="comment-date">
						<span class="human-time"><?php echo opus_get_the_human_time( strtotime( get_comment_time('c') ) ); ?></span>
						<span class="conventional-time"><?php printf( get_comment_time('F d, Y')) ?></span>
					</div>						
				</div>				
			</div>
		</div><!-- .comment-wrap -->
	<?php
}

/**
 * Display the comment form
 * 
 * @return void
 */
function opus_comment_form(){
	global $user_identity, $id;

	if ( ! comments_open() ) : ?>
	<div id="respond" class="closed-comment">
		<header id="respond-header">
			<h2 id="respond-title"><?php _e( 'Comment is closed.', 'opus' ); ?></h2>
		</header>
		<p><?php _e('Contact us if you have something important to say about this topic.', "opus"); ?></p>
	</div>
	<?php elseif (comments_open()) : ?>

	<!-- Comment Form -->
	<div id="respond" class="comment-form">
		<header id="respond-header">
			<h2 id="respond-title"><?php _e( 'What Do You Think?', 'opus' ); ?></h2>
		</header>

		<div class="cancel-comment-reply"> 
			<?php cancel_comment_reply_link(); ?>
		</div>

		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
			<p class="comment-loggedin">
				You have to be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to be able to comment.
			</p>
		<?php else : ?>
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form">
			

				<div class="submit-comment-avatar">
					<span class="comment-tail"></span>
					<?php if ( is_user_logged_in() ) : ?>
					<div class="comment-logged-in info left-info">
						<?php echo get_avatar( wp_get_current_user()->ID, 82, get_template_directory_uri() . '/images/default-avatar.jpg' ); ?>
					</div>						
					<div class="comment-logged-in info right-info">
						<p class="logged-in-as"><span class="subtitle">Logged in as</span> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a></p>	
						<p><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out</a></p>
					</div>
					<?php else : ?>
					<input type="text" name="author" class="comment-input author info left-info" placeholder="Name" id="author" size="22" tabindex="1" <?php if (isset($req)) echo "aria-required='true'"; ?> />
					<input type="text" name="email"  class="comment-input email info right-info" placeholder="Email" id="email" size="22" tabindex="2" <?php if (isset($req)) echo "aria-required='true'"; ?> />
					<input type="text" name="url"  class="comment-input url info right-info" placeholder="URL" id="url" size="22" tabindex="2" <?php if (isset($req)) echo "aria-required='true'"; ?> />
					<?php endif; ?>						
				</div>
				<div class="submit-comment-content clearfix">
					<textarea name="comment" id="comment" rows="10" tabindex="4" placeholder="Type your comment here..." class="the-content"></textarea>
					<div id="submit-wrap">
							<span class="icon"></span>
							<input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
					</div>						
					<?php comment_id_fields(); ?>
					<?php do_action('comment_form', isset($post->ID)); ?>							
				</div>
			

			</form>
		<?php endif; // If registration required and not logged in ?>
	</div>
	<?php endif;
}

/**
 * Determine if a background image needs light or dark text
 * get average luminance, by sampling $num_samples times in both x,y directions
 * 
 * @link http://stackoverflow.com/questions/5842440/background-image-dark-or-light
 * 
 * @param string filename
 * @param int divider
 * 
 * @return int luminance
 */
function opus_get_avg_luminance($filename, $num_samples=10) {
    $img = imagecreatefromjpeg($filename);

    $width = imagesx($img);
    $height = imagesy($img);

    $x_step = intval($width/$num_samples);
    $y_step = intval($height/$num_samples);

    $total_lum = 0;

    $sample_no = 1;

    for ($x=0; $x<$width; $x+=$x_step) {
        for ($y=0; $y<$height; $y+=$y_step) {

            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // choose a simple luminance formula from here
            // http://stackoverflow.com/questions/596216/formula-to-determine-brightness-of-rgb-color
            $lum = ($r+$r+$b+$g+$g+$g)/6;

            $total_lum += $lum;

            // debugging code
 //           echo "$sample_no - XY: $x,$y = $r, $g, $b = $lum<br />";
            $sample_no++;
        }
    }

    // work out the average
    $avg_lum  = $total_lum/$sample_no;

    return $avg_lum;
}

/**
 * Get embed code based on string (path to file, embed code, oEmbed supported video link)
 * 
 * @param string path to file || embed code || oEmbed-supported video link
 * 
 * @return void
 */
function opus_get_video_embed_code( $video ){
	$video_extensions = array( 'mp4', 'ogg' );
	$video_info = pathinfo( $video );

	// Check if this should be displayed using video tag
	if( isset( $video_info['extension'] ) && in_array( $video_info['extension'], $video_extensions) ){

		echo "<video controls><source src='$video'></source></video>";

	} elseif( strpos( $video, '<iframe' ) !== false ){
		// If this is embed code
		echo $video;
	} else {
		// Otherwise, assume that this is oEmbed link and get the content using built-in oEmbed mechanism
		echo wp_oembed_get( $video );
	}
}

/**
 * Get current page's mention
 * 
 * @param string taxonomy name
 * 
 * @return bool|string taxonomy term
 */
function opus_get_current_tax( $taxonomy_name ){
	global $wp_query;

	if( isset( $wp_query->query[$taxonomy_name] ) ){
		return $wp_query->query[$taxonomy_name];
	} else {
		return false;
	}
}

/**
 * Removing widht and height attribute from images
 */
function opus_remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}