


	var Section = Backbone.View.extend({

		hasLightbox: true,

		events: {

			'change .type-radio': 'changeView',

		},

		initialize: function(){

			var self = this;



		},


		changeView: function( el ){


			console.log( jQuery( el.target ).val() );


		}

	});


	jQuery( document ).ready( function( $ ){

		var sections = [];

		$('.section-wrapper').each( function( index, obj ){

			var col = new Section( { el: obj } );
			sections.push( col );

		});


		$('#addSection').on( 'click', function(){

			var data = {
				action: 'createSection',
				post_id: $( this ).data('post_id')
			}

			$.post( ajaxurl, data, function( response ){

				$('#section-container').append( response );

				setColumns();
				
			});

		});


	});