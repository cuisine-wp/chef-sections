


	var Column = Backbone.View.extend({

		hasLightbox: true,

		columnId: '',
		sectionId: '',
		postId: '',

		/**
		 * Events for this View
		 * @return object
		 */
		events: {

			'click .edit-btn': 'launchLightbox',
			'click .lightbox-modal-close': 'closeLightbox',
			'click #save-column': 'saveColumn',

		},


		/**
		 * Events for this View
		 * @return this
		 */
		initialize: function(){

			var self = this;

			self.columnId = self.$el.data( 'id' );
			self.sectionId = self.$el.data( 'section_id' );
			self.postId = self.$el.data( 'post_id' );

			return this;
		},


		/**
		 * Launch this columns lightbox
		 * @param  event e 
		 * @return void
		 */
		launchLightbox: function( e ){

			var self = this;
			e.preventDefault();

		
			if( self.$('.edit-btn' ).hasClass( 'no-lightbox' ) ){
				//open the media-modal

			}else{

				self.$('.lightbox').addClass('active');

			}

		},

		/**
		 * Close this columns lightbox
		 * @param  event e 
		 * @return void
		 */
		closeLightbox: function( e ){

			var self = this;
			e.preventDefault();

			self.$('.lightbox').removeClass( 'active' );

		},


		/**
		 * Save this columns contents
		 * @param  event e 
		 * @return bool
		 */
		saveColumn: function( e ){

			var self = this;
			e.preventDefault();

			
			var properties = {};
			var inputs = self.$('.lightbox .field-wrapper .field');

			for( var i = 0; i <= inputs.length; i++ ){

				var input = jQuery( inputs[ i ] );

				if( input.val() !== undefined && input.attr( 'name' ) !== undefined )
					properties[ input.attr( 'name' ) ] = input.val();

			}

			//add the editor content
			if( self.$( '.lightbox .editor' ).length > 0 ){
				properties[ 'content' ] = tinyMCE.activeEditor.getContent();
			}


			var data = {
						'action' 		: 'saveColumnProperties',
						'post_id' 		: self.postId,
						'column_id'		: self.columnId,
						'properties'	: properties
			};


			jQuery.post( ajaxurl, data, function( response ){

				//error handeling


			});


		}
	});



	jQuery( document ).ready( function( $ ){

		var columns = [];

		$('.section-wrapper .column' ).each( function( index, obj ){

			var col = new Column( { el: obj } );
			columns.push( col );

		});

	});