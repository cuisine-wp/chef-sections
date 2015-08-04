define([
	
	'jquery',
	'isotope',
	'imagesloaded'
	

], function( $, Isotope ){

	$('#main').imagesLoaded( function() {
	
		/**
		*		ISOTOPE:
		*
		*/

		var iso = new Isotope( '.masonry', {
			itemSelector: '.block',
			layoutMode: 'masonry',
			masonry: {
				gutter: 15,
				columnWidth: '.block'
		  	}
		});
	
	});

});