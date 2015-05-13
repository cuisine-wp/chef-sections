


	var Column = Backbone.View.extend({

		hasLightbox: true,

		events: {

			'click .edit-btn': 'launchLightbox'

		},

		initialize: function(){

			var self = this;

		},



		launchLightbox: function( e ){

			var self = this;
			e.preventDefault();

		//	if( self.$('.edit-btn' ).hasClass( 'no-lightbox' ) ){
				//open the media-modal

		//	}else{

				self.$('.lightbox').addClass('active');

				console.log( 'wank' );
		//	}

		},


	});



	jQuery( document ).ready( function( $ ){

		var columns = [];

		$('.section-wrapper .column' ).each( function( index, obj ){

			var col = new Column( { el: obj } );
			columns.push( col );

		});

	});