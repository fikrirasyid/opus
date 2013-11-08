jQuery(document).ready(function($) { 
	// Normalizing entry-content items' width
	function opus_normalize_media(){
		var item 	= $(this);
		var item_width = item.width();
		var content_width = $('#content .entry-content').width();
		if( item_width > content_width ){
			item.removeAttr('width').css({'width' : '100%'}).is('img div').removeAttr('height');
		}
	}	
	$('.entry-content img, .entry-content .wp-caption, .entry-content iframe').each(opus_normalize_media);
});