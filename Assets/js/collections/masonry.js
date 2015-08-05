define([
	
	'jquery',
	'isotope',
	'imagesloaded'
	

], function( $, Isotope ){

	$( document ).ready(function(){
	
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