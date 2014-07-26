<?php
	// Update the date in given condition
	if( isset( $GLOBALS['wp_query'] ) && !is_sticky() ){	
		
		if( !isset( $GLOBALS['wp_query']->opus_date ) ){		
			$date_index = 0;
		} else {
			$date_index = $GLOBALS['wp_query']->opus_date;
		}

		$timestamp 	= strtotime( $post->post_date );

		$date 		= date( 'Y-m-d', $timestamp );

		if( $date_index != $date ){

			echo '<h4 class="article-day"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';

		}

		$GLOBALS['wp_query']->opus_date = $date;

	}