/*
 * Copyright 2013, Fikri Rasyid - http://fikrirasyid.com
 * License: Distributed under the terms of GNU General Public License
*/
jQuery(document).ready(function($) { 
	// Normalizing entry-content items' width
	function opus_normalize_media(){
		var item 	= $(this);
		var item_width = item.width();
		var content_width = $('#content .entry-content').width();
		if( item_width > content_width ){
			item.removeAttr('width').css({'width' : '100%'});

			if( item.is('img, div') ){
				item.removeAttr('height');
			}
		}
	}	

	// On-load interface enhancement
	function opus_page_load(){
		// Normalizing interface
		$('.entry-header iframe').attr({ 'width' : '100%' });
		$('.entry-content img, .entry-content .wp-caption, .entry-content iframe, .entry-content embed, .widget img, .widget select').each(opus_normalize_media);
		$('.entry-content .attachment img').removeAttr('width').removeAttr('height').css({ 'width' : '100%' });

		// For Posts which have more than one category
		$('.entry-category').each(function(){
			var cat = $(this);
			if( cat.find('li').size() > 1 ){
				cat.find('sup').remove();
				cat.find('li:first a').append('<sup>+</sup>');
				cat.find('li:gt(0)').hide();

				cat.hover(
					function(){
						cat.find('li:gt(0)').show();
					},
					function(){
						cat.find('li:gt(0)').hide();
					}
				);
			}
		});

		// Article day's UI
		var content_width 		= $('#content').outerWidth();
		var window_width 		= $(window).width();
		var article_day_margin	= 0 - ( ( window_width - content_width ) / 2 );
		$('.article-day .border').css({ 'left' : article_day_margin, 'right' : article_day_margin });

		if( $('article.sticky').length < 1 ){
			// $('.article-day:first').addClass( 'on-page-cover' );			
		}

		// Parallax Cover
	 	if( $('#page-cover').length > 0  && !is_touch_device() ){
			var page_cover_height = $('#header').height();
			var page_cover_top = 0 - ( page_cover_height / 10 );
			$('#page-cover').css({ 'position' : 'fixed', 'margin-top' : 0, 'height' : page_cover_height });
			$('#page-cover img').css({ 'margin-top' : page_cover_top });
		}				
	}

	// Perform page adjustment after all assets have been loaded
	$(window).load(function(){
		opus_page_load();
	});

	// Adjust page on window resize
	$(window).resize(function(){
		opus_page_load();
	});

	// Trigger on-load event when new contents are appended by Jetpack
	$( document.body ).on( 'post-load', function() {
		opus_page_load();

		// Adjust article-day, month, and year for every first article of Jetpack-powered articles load
		var new_articles_wrap 			= $('.infinite-wrap:last');
		var first_article_marker 		= new_articles_wrap.find('.article-marker:first');
		var first_article_marker_date 	= first_article_marker.attr( 'data-date' );
		var first_article_marker_month 	= first_article_marker.attr( 'data-month' );		
		var first_article_marker_year 	= first_article_marker.attr( 'data-year' );

		var last_article_marker 		= new_articles_wrap.find('.article-marker:last');
		var last_article_marker_date 	= last_article_marker.attr( 'data-date' );
		var last_article_marker_month 	= last_article_marker.attr( 'data-month' );		
		var last_article_marker_year 	= last_article_marker.attr( 'data-year' );

		// If current article equals to latest' post, hide em
		if( $('.article-day[data-date="'+ first_article_marker_date +'"]').size() > 1 ){
			new_articles_wrap.find('.article-day:first').hide();
		}

		// If the first article of the loaded post is new month, display article-month
		if( first_article_marker_month != $('body').attr('data-month') ){
			new_articles_wrap.prepend('<h3 class="article-month">' + first_article_marker_month + ' ' + first_article_marker_year + '</h3>');
		}

		// If the first article of the loaded post is new year, display article-month
		if( first_article_marker_year != $('body').attr('data-year') ){
			new_articles_wrap.prepend('<h3 class="article-year">' + first_article_marker_year +'</h3>');						
		}

		// Update the body data
    	$('body').attr({
    		'data-year' 	: last_article_marker_year,
    		'data-month' 	: last_article_marker_month,
    		'data-date' 	: last_article_marker_date
    	});
    } );

    // Jetpack adjustment for article-day / month / year on page-load
    if( $('body').is('.infinite-scroll') ){
    	var last_marker = $('.article-marker:last');
    	var last_year 	= last_marker.attr( 'data-year' );
    	var last_month 	= last_marker.attr( 'data-month' );
    	var last_date 	= last_marker.attr( 'data-date' );

    	// Attach the date
    	$('body').attr({
    		'data-year' 	: last_year,
    		'data-month' 	: last_month,
    		'data-date' 	: last_date
    	});
    }	

	/**
	 * Top Navigation and Parallax Mechanism
	 */
	// Top & Toggle mechanism
	var scroll_init = 0;
	var header_height = $('#header').height();

	$(window).scroll(function(event){
		var scroll_offset = $(this).scrollTop();

		// Determine the status of this: scroll up or down?
		if( scroll_offset > scroll_init ){
			$('body').addClass('scroll-down').removeClass('scroll-up');
		} else {
			$('body').addClass('scroll-up').removeClass('scroll-down');
		}

		// Ignore scroll status if we're on the top of the page
		if( scroll_offset < header_height ){
			$('body').addClass('top-of-page');
		} else {
			$('body').removeClass('top-of-page');			
		}

		if( 40 > scroll_offset ){
			$('body').addClass('top-most-of-page');
		} else {
			$('body').removeClass('top-most-of-page');			
		}

		// Parallaxing
	 	if( $('#page-cover').length > 0  && !is_touch_device() ){
			var page_cover_height = $('#header').height();
			var page_cover_top = 0 - ( page_cover_height / 10 );
			var cover_height = page_cover_height - scroll_offset;
			var cover_image_bottom = ( scroll_offset / 10 ) + page_cover_top;

			if( scroll_offset > page_cover_height ){
				var cover_opacity = 0;
			} else {
				var cover_opacity = ( page_cover_height - scroll_offset ) / page_cover_height;
			}

			$('#page-cover').css({ 'height' : cover_height, 'opacity' : cover_opacity } );

			if( cover_height > 0 && cover_height <= page_cover_height ){
				$('#page-cover').css({ 'height' : cover_height } ); 	
				$('#page-cover img').css({ 'margin-top' : cover_image_bottom });		
			}		
		}

		scroll_init = scroll_offset;
	});

	var top_nav_container_top_raw = $('.top-nav-container').css('top');
	var top_nav_container_top = parseInt( top_nav_container_top_raw.replace( 'px', '' ) );

	$('#top-nav-toggle').click(function(){
		if( $('body').is('.expanded') ){
			$('#top-nav .top-nav-container').animate({ 'top' : '200%'}, 200, function(){
				$('body').removeClass('expanded');
				$(this).attr('style', '');
			});
		} else {
			$('#top-nav .top-nav-container').css({ 'top' : '200%', 'display' : 'block' }).animate({ 'top' : top_nav_container_top }, 200, function(){
				$('body').addClass('expanded');
				$(this).attr('style', '');
			});
		}
	});

	/**
	 * Closing #top-nav using keyboard (escape) 
	 */
	$(document).keyup(function(e){
		if ( e.keyCode == 27 && $('body.expanded').length > 0){
			$('#top-nav .top-nav-container').animate({ 'top' : '200%'}, 200, function(){
				$('body').removeClass('expanded');
				$(this).attr('style', '');
			});
		}
	});

	// For Post Formated by Quote and Having Featured Image
	if( $('body').is('.single-format-quote') && $('#page-cover img').length > 0 ){
		var body_height = $('body').outerHeight();

		$('#page-cover').css({ 'height' : body_height });
		$('#page-cover img').css({ 
			'height' : '100%',
			'width' : 'auto',
		});
		$('#page-cover').append('<span class="shadow"></span>');
		$('#page-cover .shadow').css({
			'display' : 'block',
			'width' : '100%',
			'height' : '100%',
			'position' : 'absolute',
			'top' : '0',
			'left' : '0',
			'background-color' : 'white',
			'opacity' : .8
		});
		$('#content article').css({
			'background-color' : 'transparent'
		});
	}

	/**
	* Civil Footnotes Support
	* Slide the window instead of jumping it
	*/
	$('#main').on( 'click', 'a[rel="footnote"], a.backlink', function(e){
		e.preventDefault();
		var target_id = $(this).attr('href');
		var respond_offset = $(target_id).offset();

		$('html, body').animate({
			scrollTop : respond_offset.top - 90
		});
	});		
	
	// Show conventional time when user hovering .entry-date
	$('#content').on( 'mouseenter', '.entry-meta', function(){
		$(this).find('.on-load').hide();
		$(this).find('.on-hover').show();
	});

	$('#content').on( 'mouseleave', '.entry-meta', function(){
		$(this).find('.on-load').show();
		$(this).find('.on-hover').hide();			
	});

	// Toggle footer
	$('#colophon-toggle').click( function(e){
		e.preventDefault();

		$(this).toggleClass( 'active' );

		if( $('#colophon').is(':visible') ){
			$('body').css('overflow', 'auto' );
			$('#colophon').animate({ 'top' : '99%'}, 400, function(){
				$('#colophon').hide();
			} );
		} else {
			$('body').css('overflow', 'hidden' );
			$('#colophon').show();
			$('#colophon').animate({ 'top' : '25%' });
		}
	} );	
});

function is_touch_device() {
	return 'ontouchstart' in window // works on most browsers 
		|| 'onmsgesturechange' in window; // works on ie10
};