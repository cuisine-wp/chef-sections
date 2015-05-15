


	var Column = Backbone.View.extend({

		hasLightbox: true,

		columnId: '',
		fullId: '',
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
			'change > .field-wrapper .type-select': 'changeType',

		},


		/**
		 * Events for this View
		 * @return this
		 */
		initialize: function(){

			var self = this;

			self.fullId = self.$el.data('id');
			self.columnId = self.$el.data( 'column_id' );
			self.sectionId = self.$el.data( 'section_id' );
			self.postId = self.$el.data( 'post_id' );

			if( self.$( '.lightbox .editor textarea' ).length > 0 ){
			
			//	console.log( tinyMCE.init() );

			}

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
					
				var options = Media.sanitize_uploader_options( [] );
				Media.uploader( options, function( attachments ){

					console.log( attachments );

				});


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

			if( e !== undefined )
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
			var inputs = self.$('.lightbox .field-wrapper .field, .lightbox .field-wrapper .subfield:checked');

			
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
						'column_id'		: self.fullId,
						'properties'	: properties
			};


			jQuery.post( ajaxurl, data, function( response ){

				//error handeling
				self.closeLightbox();

			});
		},

		/**
		 * Change the type of a column
		 * 
		 * @return void
		 */
		changeType: function( el ){

			var self = this;
			var type = jQuery( el.target ).val();

			var data = {
						'action' 		: 'saveColumnType',
						'post_id' 		: self.postId,
						'column_id'		: self.columnId,
						'section_id'	: self.sectionId,
						'type'			: type
			};



			jQuery.post( ajaxurl, data, function( response ){

				self.$el.replaceWith( response );
				setColumns();

			});

		}

	});


	jQuery( document ).ready( function( $ ){


		setColumns();

	});


	function setColumns(){

		jQuery('.column' ).each( function( index, obj ){

			var col = new Column( { el: obj } );

		});
	}