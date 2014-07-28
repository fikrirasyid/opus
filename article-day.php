<?php
	// Update the date in given condition
	if( isset( $GLOBALS['wp_query'] ) && is_sticky() ){
		$GLOBALS['wp_query']->has_sticky = true;
	}

	if( isset( $GLOBALS['wp_query'] ) && !is_sticky() ){	
		
		// Define date index
		if( !isset( $GLOBALS['wp_query']->opus_date ) ){		
			$date_index = 0;
		} else {
			$date_index = $GLOBALS['wp_query']->opus_date;
		}
		
		// Define month index
		if( !isset( $GLOBALS['wp_query']->opus_month ) ){		
			$month_index = 0;
		} else {
			$month_index = $GLOBALS['wp_query']->opus_month;
		}

		// Define timestamp
		$timestamp 	= strtotime( $post->post_date );

		// Define date, month and year
		$date 		= date( 'Y-m-d', $timestamp );
		$month 		= date( 'm', $timestamp );



		// Print article-month
		if( $month_index > 0 && $month_index != $month ){
			echo '<h3 class="article-month" data-article-month="'. date( 'm-Y', $timestamp ) .'">'. date( 'F Y', $timestamp ) .'</h3>';
		}

		// Print article-day
		if( $date_index != $date ){

			if( !isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 ){
				echo '<h4 class="article-day on-page-cover"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else if( isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 && !isset( $GLOBALS['wp_query']->query['nopaging'] ) ){
				echo '<h4 class="article-day on-page-cover"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else {
				echo '<h4 class="article-day"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';				
			}


		}

		// Set globals
		$GLOBALS['wp_query']->opus_date 	= $date;
		$GLOBALS['wp_query']->opus_month 	= $month;

	}