


	var Section = {

		el: '',

		init: function( $el ){

			self = this;
			self.el = $el;


		},


	}



	jQuery( document ).ready( function( $ ){

		$('.section-wrapper').each( function(){

			Section.init( $( this ) );

		});


		$('#addSection').on( 'click', function(){

			var data = {
				action: 'create_section'
			}

			$.post( ajaxurl, data, function( response ){

				console.log( response );


			});

		});


	});