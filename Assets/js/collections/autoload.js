define([
	
	'jquery',
	'autoload',
	'isotope',

], function( $, Autoload ){

	$('.autoload').AutoLoad({

		message: $('.autoload').data('msg'),
		postId: $('.autoload').data('post'),
		column: $('.autoload').data('id'),
		section: $('.autoload').data('section_id'),
		pageNumber: $( '.autoload' ).data('page'),
		loaderContent: '<div class="spinner"><div class="mask"><div class="line"></div></div></div>',
		onComplete: function(){

			var page = parseInt( $( '.autoload' ).data('page') ) + 1;
			$( '.autoload' ).data('page', page );

			if( $( '.autoload' ).hasClass( '.masonry' ) ){

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