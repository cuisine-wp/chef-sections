


	var Section = Backbone.View.extend({

		hasLightbox: true,

		sectionId: '',
		postId: '',

		events: {

			'change .section-controls .type-radio': 'changeView',

		},

		initialize: function(){

			var self = this;

			self.sectionId = self.$el.data( 'section_id' );
			self.postId = self.$el.data( 'post_id' );


		},

		/**
		 * Change the view of a section
		 * 
		 * @param  Element el
		 * @return void
		 */
		changeView: function( el ){

			var self = this;
			self.showLoader( jQuery );
			
			var view = jQuery( el.target ).val();

			var data = {

				action: 'changeView',
				section_id: self.sectionId,
				post_id: self.postId,
				view: view

			}

			jQuery.post( ajaxurl, data, function( response ){
				//console.log( response );				
				self.$el.replaceWith( response );

				setSections();
				setColumns();

			});
		},


		showLoader: function( $ ){

			var self = this;
			self.$( '.loader' ).addClass( 'show' );
		}


	});





	jQuery( document ).ready( function( $ ){

		setSections();

		$('#addSection').on( 'click', function(){

			var data = {
				action: 'createSection',
				post_id: $( this ).data('post_id')
			}

			$.post( ajaxurl, data, function( response ){

				$('#section-container').append( response );

				setSections();
				setColumns();
				
			});

		});

	});



	function setSections(){

		jQuery('.section-wrapper').each( function( index, obj ){

			var sec = new Section( { el: obj } );

		});
	}
