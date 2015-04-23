/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Link color.
	wp.customize( 'site_color', function( value ) {
		value.bind( function( to ) {						
			// Updating the color scheme
			var site_color = to.substr( 1 );

			$.getJSON( opus_customizer_params.generate_color_scheme_endpoint, { site_color : site_color }, function( data ){
				if( true == data.status ){
					$('body').append( '<style type="text/css" media="screen">'+data.colorscheme+'</style>');
				} else {
					alert( opus_customizer_params.generate_color_scheme_error_message );
				}
			});
		} );
	} );	

	// Clear temporary settings if customizer is closed
	window.addEventListener("beforeunload", function (e) {
		$.post( opus_customizer_params.clear_customizer_settings );
	});		
} )( jQuery );
