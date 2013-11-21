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
	$('.entry-content img, .entry-content .wp-caption, .entry-content iframe, .entry-content embed').each(opus_normalize_media);
	$('.entry-content .attachment img').removeAttr('width').removeAttr('height').css({ 'width' : '100%' });

	/**
	 * Top Navigation Mechanism
	 */
	$(window).scroll(function(){
		var window_offset = $(window).scrollTop();
		if( window_offset > 0 ){
			$('#top-nav').addClass('scrolled');
		} else {
			$('#top-nav').removeClass('scrolled');
		}
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
	 * For Posts which have more than one category
	 */
	$('.entry-category').each(function(){
		var cat = $(this);
		if( cat.find('li').size() > 1 ){
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

	/**
	 * For Post Formated by Quote and Having Featured Image
	 */
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
	 * Show conventional time when user hovering .entry-date
	 */
	$('.entry-meta').hover(
		function(){
			$(this).find('.human-time').hide();
			$(this).find('.conventional-time').show();
		}, 
		function(){
			$(this).find('.human-time').show();
			$(this).find('.conventional-time').hide();			
		}
	);
});