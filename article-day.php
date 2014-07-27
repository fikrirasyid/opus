<?php
	// Update the date in given condition
	if( isset( $GLOBALS['wp_query'] ) && is_sticky() ){
		$GLOBALS['wp_query']->has_sticky = true;
	}

	if( isset( $GLOBALS['wp_query'] ) && !is_sticky() ){	
		
		if( !isset( $GLOBALS['wp_query']->opus_date ) ){		
			$date_index = 0;
		} else {
			$date_index = $GLOBALS['wp_query']->opus_date;
		}

		$timestamp 	= strtotime( $post->post_date );

		$date 		= date( 'Y-m-d', $timestamp );

		if( $date_index != $date ){

			if( !isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 ){
				echo '<h4 class="article-day on-page-cover"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else if( isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 && !isset( $GLOBALS['wp_query']->query['nopaging'] ) ){
				echo '<h4 class="article-day on-page-cover"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else {
				echo '<h4 class="article-day"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';				
			}


		}

		$GLOBALS['wp_query']->opus_date = $date;

	}