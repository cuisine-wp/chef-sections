define([
	
	'jquery',
	'isotope',
	'imagesloaded'
	

], function( $, Isotope, Imagesloaded ){


	Imagesloaded( '#main', function(){
	
		/* ISOTOPE */
		var iso = new Isotope( '.masonry', {
			itemSelector: '.block',
			layoutMode: 'masonry',
			masonry: {
				gutter: 30,
				columnWidth: '.block'
		  	}
		});

	});
	
});