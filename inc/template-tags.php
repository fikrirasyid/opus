<?php
/**
 * Prints HTML of categories of current post
 */
function opus_the_category(){
	echo '<ul class="entry-category"><li>';
	the_category( '</li><li>', 'single' );
	echo '</li></ul>';
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function opus_posted_on() {
	printf(
		__( '
			<div class="entry-posted-on">
				<span class="entry-author author vcard"><a href="%1$s" class="url fn n" title="%2$s" rel="author">%3$s</a></span> 
				<span class="entry-author-action">published this</span> 
				<span class="entry-date">
					<span class="human-time">%4$s</span>
					<span class="conventional-time">%5$s</span>
				</span>
			</div>
		', 'opus' ),
		esc_url( get_permalink() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		get_the_author(),
		esc_html( opus_get_the_human_time( strtotime( get_the_date('c') ) ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Returns human readable time
 */
function opus_get_the_human_time($post_time = 0){
	$timestamp = current_time( 'timestamp' ) - $post_time;

	$human_time = '';
	$minute 	= 60;
	$hour 		= 60 * $minute;
	$day 		= 24 * $hour;
	$week 		= 7 * $day;
	$month 		= 30 * $day;
	$year 		= 365 * $day;

	if( ( $timestamp / $year ) >= 1 ){
		// Calculate time
		$post_year 	= floor( $timestamp / $year );
		$post_month = floor( ( $timestamp % $year ) / $month );

		// Convert into text
		$post_year_text = opus_plural_sensitive_noun('year', $post_year);
		$post_month_text = opus_plural_sensitive_noun('month', $post_month);

		// Set Time
		$human_time = __(sprintf("%s and %s ago", $post_year_text, $post_month_text), 'opus');
	} elseif( ( $timestamp / $month ) >= 1 ) {
		// Calculate time
		$post_month = floor( $timestamp / $month );

		// Convert into text
		$post_month_text = opus_plural_sensitive_noun('month', $post_month);

		// Set Time
		$human_time = __(sprintf("%s ago", $post_month_text), 'opus');
	} elseif( ( $timestamp / $week ) >= 1 ) {	
		// Calculate time
		$post_week = floor( $timestamp / $week );	

		// Convert into text
		$post_week_text = opus_plural_sensitive_noun('week', $post_week);

		// Set Time
		$human_time = __(sprintf("%s ago", $post_week_text), 'opus');
	} elseif( ( $timestamp / $day ) >= 1 ) {
		// Calculate time
		$post_day = floor( $timestamp / $day );	

		// Convert into text
		$post_day_text = opus_plural_sensitive_noun('day', $post_day);

		// Set Time
		$human_time = __(sprintf("%s ago", $post_day_text), 'opus');
	} elseif( ( $timestamp / $hour ) >= 1 ) {
		// Calculate time
		$post_hour = floor( $timestamp / $hour );	

		// Convert into text
		$post_hour_text = opus_plural_sensitive_noun('hour', $post_hour);

		// Set Time
		$human_time = __(sprintf("%s ago", $post_hour_text), 'opus');
	} else {
		// Calculate time
		$post_minute = floor( $timestamp / $minute );	

		// Convert into text
		$post_minute_text = opus_plural_sensitive_noun('minute', $post_minute);

		// Set Time
		$human_time = __(sprintf("%s ago", $post_minute_text), 'opus');
	}

	return $human_time;
}

/**
 * Returns time sensitive noun
 */
function opus_plural_sensitive_noun( $noun = 'apple', $number = 0){
	$first_char 		= substr( $noun, 0, 1 );
	$last_char 			= substr( $noun, -1);
	$vocal 				= array('a', 'i', 'u', 'e', 'o');
	$article_exception  = array('hour');

	// Get correct article
	if(in_array($first_char, $vocal) || in_array($noun, $article_exception)){
		$article = 'an';
	} else {
		$article = 'a';
	}

	// Get noun
	if($number > 1){
		$word_number = opus_convert_number_to_words($number);

		if($last_char == 's'){
			$noun = $noun . 'es';
		} else {
			$noun = $noun . 's';
		}
	} elseif($number == 1){
		$word_number = $article;
	} else{
		$word_number = __('less then '.$article, 'opus');
	}

	return $word_number . ' ' . $noun;
}

/*
 * Translate number into words by Karl Rixon
 * Source: http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
 */
function opus_convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}

/**
 * Returns domain name of given URL
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
 */
function opus_comments_popup_link(){
	comments_popup_link( __( 'No Response Yet. <strong>Add Yours</strong>', '_s' ), __( '1 Response Shared. <strong>Add Yours</strong>', '_s' ), __( '% Responses Shared. <strong>Add Yours</strong>', '_s' ) );
}

/**
 * Display navigation to next/previous pages when applicable
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

		<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="label">%title</span> <span class="meta-nav">' . _x( '', 'Next post link', 'opus' ) . '</span>' ); ?>
		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '', 'Previous post link', 'opus' ) . '</span> <span class="label">%title</span>' ); ?>

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