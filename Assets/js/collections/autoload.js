define([
	
	'jquery',
	'autoload',
	'isotope',
	'imagesloaded'

], function( $, Autoload, Isotope ){

	$( document ).ready(function(){

		var loader = $('#autoloader').clone();
		$('#autoloader').remove();

		$('.autoload').AutoLoad({
	
			message: $('.autoload').data('msg'),
			postId: $('.autoload').data('post'),
			column: $('.autoload').data('id'),
			section: $('.autoload').data('section_id'),
			pageNumber: $( '.autoload' ).data('page'),
			loaderContent: loader[0].outerHTML,
	
			onComplete: function(){
	
				var page = parseInt( $( '.autoload' ).data('page') ) + 1;
				$( '.autoload' ).data('page', page );
	
				if( $( '.autoload' ).hasClass( 'masonry' ) ){
	
					//recalculate masonry:
					var iso = new Isotope( '.masonry', {
						itemSelector: '.block',
						layoutMode: 'masonry',
						masonry: {
							gutter: 15,
							columnWidth: '.block'
					 	}
					});
				}
	
			}
	
		});

	});

});