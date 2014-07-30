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
		
		// Define year index
		if( !isset( $GLOBALS['wp_query']->opus_year ) ){		
			$year_index = 0;
		} else {
			$year_index = $GLOBALS['wp_query']->opus_year;
		}

		// Define timestamp
		$timestamp 	= strtotime( $post->post_date );

		// Define date, month and year
		$date 		= date( 'Y-m-d', $timestamp );
		$month 		= date( 'm', $timestamp );
		$year 		= date( 'Y', $timestamp );


		// Print article-year
		if( $year_index > 0 && $year_index != $year ){
			echo '<h3 class="article-year">'. date( 'Y', $timestamp ) .'</h3>';
		}

		// Print article-month
		if( $month_index > 0 && $month_index != $month ){
			echo '<h3 class="article-month">'. date( 'F Y', $timestamp ) .'</h3>';
		}

		// Print article-day
		if( $date_index != $date ){

			if( !isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 ){
				echo '<h4 class="article-day on-page-cover" data-date="'. $date .'"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else if( isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 && !isset( $GLOBALS['wp_query']->query['nopaging'] ) ){
				echo '<h4 class="article-day on-page-cover" data-date="'. $date .'"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';
			} else {
				echo '<h4 class="article-day" data-date="'. $date .'"><span class="label">' . date( 'l, d F Y', $timestamp ) . '</span><span class="border"></span></h4>';				
			}

		}

		// Print marker
		$month_label = date( 'F', $timestamp );
		echo "<span style='display: none;' class='article-marker' data-date='$date' data-month='$month_label' data-year='$year'></span>";

		// Set globals
		$GLOBALS['wp_query']->opus_date 	= $date;
		$GLOBALS['wp_query']->opus_month 	= $month;
		$GLOBALS['wp_query']->opus_year 	= $year;

	}