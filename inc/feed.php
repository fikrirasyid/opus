<?php

/**
 * Feed customization
 */
class Opus_Feed{
	/**
	 *  Init feed customization
	 */
	function __construct(){
		add_filter( 'the_excerpt_rss', array( $this, 'prepend_media' ) );
		add_filter( 'the_content_feed', array( $this, 'prepend_media' ) );
	}

	/**
	 * Prepending media to feed item
	 * 
	 * @param string content
	 * 
	 * @return string modified contet
	 */
	function prepend_media( $content ){
		global $post;

		$content = '';

		// Prepend rich media media to RSS item
		
		if( has_post_thumbnail( $post->ID ) && 'video' != get_post_format( $post->ID ) ) { // Featured for non-video post format

			$content .= '<p>' . get_the_post_thumbnail( $post->ID, 'featured' ) . '</p>';

		} elseif( 'link' == get_post_format( $post->ID ) ) { // Link

			$entry_custom_meta = get_post_custom( $post->ID ); 
			
			if( isset( $entry_custom_meta['_format_link_url'] ) ){

				$content .= '<p>'. sprintf( __( '<a href="%1$s" rel="bookmark">link to %2$s</a>', 'opus' ), $entry_custom_meta['_format_link_url'][0], opus_get_domain_name( $entry_custom_meta['_format_link_url'][0] ) ) .'</p>';

			}			

		} elseif( 'video' == get_post_format( $post->ID ) ){ // Video 

			$video = get_post_meta( $post->ID, '_format_video_embed', true );
			
			if( $video ){
				
				ob_start();

				opus_get_video_embed_code( $video );

				$video = ob_get_clean();

				$content .= $video;
			
			}
		}

		$content .= get_the_content();

		return $content;
	}
}