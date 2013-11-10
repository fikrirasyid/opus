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
	$('.entry-content img, .entry-content .wp-caption, .entry-content iframe').each(opus_normalize_media);

	/**
	 * Top Navigation Mechanism
	 */
	$(window).scroll(function(){
		var window_offset = $(window).scrollTop();
		var main_offset = $('#main').offset();
		if( window_offset > main_offset.top ){
			$('#top-nav').addClass('scrolled');
		} else {
			$('#top-nav').removeClass('scrolled');
		}
	});

	$('#top-nav-toggle').click(function(){
		$('#top-nav').toggleClass('expanded');
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
});