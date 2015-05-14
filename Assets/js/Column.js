


	var Column = Backbone.View.extend({

		hasLightbox: true,

		events: {

			'click .edit-btn': 'launchLightbox',
			'click .lightbox-modal-close': 'closeLightbox'

		},

		initialize: function(){

			var self = this;



		},



		launchLightbox: function( e ){

			var self = this;
			e.preventDefault();

			setTimeout( function(){
				console.log( 'editor: '+ tinyMCE.activeEditor.getContent() );
					
			}, 1000 );


			setTimeout( function(){
				console.log( 'editor: '+ tinyMCE.activeEditor.getContent() );
					
			}, 4000 );
			//console.log(  );

			if( self.$('.edit-btn' ).hasClass( 'no-lightbox' ) ){
				//open the media-modal

			}else{

				self.$('.lightbox').addClass('active');

				console.log( 'wank' );
			}

		},

		closeLightbox: function( e ){

			var self = this;
			e.preventDefault();

			self.$('.lightbox').removeClass( 'active' );

		}


	});



	jQuery( document ).ready( function( $ ){

		var columns = [];

		$('.section-wrapper .column' ).each( function( index, obj ){

			var col = new Column( { el: obj } );
			columns.push( col );

		});

	});