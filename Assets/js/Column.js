


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
			'change .column-controls .type-select': 'changeType',

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
		 * Refresh the column html 
		 * 
		 * @return void
		 */
		refresh: function(){

			var self = this;

			self.$( '.loader' ).addClass( 'show' );

			var data = {

				action: 'refreshColumn',
				column_id: self.columnId,
				post_id: self.postId,
				section_id: self.sectionId,
				type: self.$( '.column-controls .type-select' ).val()
			}

			jQuery.post( ajaxurl, data, function( response ){

				
				self.$el.replaceWith( response );
				setColumns();

			});

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
					
				self.mediaLightbox();

			}else{

				self.$('.lightbox').addClass('active');

			}

		},

		/**
		 * Show a media lightbox
		 * 
		 * @return void
		 */
		mediaLightbox: function(){
			
			var self = this;

			var options = {
				title:'Uploaden',
				button:'Opslaan',
				//media_type:'image',
				multiple:false,
				self: self,	
			}


			var properties = {};
			var fullId = self.fullId;

			Media.uploader( options, function( attachment, options ){
				console.log( attachment );
				var properties = {

					id: attachment.id,
					thumb:  ( attachment.sizes.thumbnail !== undefined ) ?
							  attachment.sizes.thumbnail.url : 'false',
					medium: ( attachment.sizes.medium !== undefined ) ?
							  attachment.sizes.medium.url : 'false',
					large:  ( attachment.sizes.large !== undefined ) ?
							  attachment.sizes.large.url : 'false',
					full:   ( attachment.sizes.full !== undefined ) ?
							  attachment.sizes.full.url : 'false',

					orientation: attachment.sizes.full.orientation

				}
				options.self.saveProperties( properties );
			});
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
			//var inputs = self.$('.lightbox').serializeArray();
			
			console.log( inputs );

			for( var i = 0; i <= inputs.length; i++ ){

				var input = jQuery( inputs[ i ] );

				if( input.val() !== undefined && input.attr( 'name' ) !== undefined )
					properties[ input.attr( 'name' ) ] = input.val();

			}


			//add the editor content
			if( self.$( '.lightbox .editor' ).length > 0 ){
				properties[ 'content' ] = tinyMCE.activeEditor.getContent();
			}


			//add multi-dimensional arrays:
			if( self.$('.multi').length > 0 ){
				properties = self.getMultiFields( properties );
			}


		//	self.saveProperties( properties );

		},


		getMultiFields: function( properties ){

			var self = this;
			var inputs = self.$('.lightbox .field-wrapper .multi');
			var ret = {};
/*
			retloop: for( var i = 0; i <= inputs.length; i++ ){

				var input = jQuery( inputs[ i ] );
				var val = input.val();
				var name = input.attr('name');

       			var parts = name.split('[');       
       			var last = ret;
		
        		for (var i in parts) {
        		    var part = parts[i];
        		    if (part.substr(-1) == ']') {
        		        part = part.substr(0, part.length - 1);
        		    }
		
        		    if (i == parts.length - 1) {
        		        last[part] = val;
        		        continue retloop;
        		    } else if (!last.hasOwnProperty(part)) {
        		        last[part] = {};
        		    }
        		    last = last[part];
        		}
       			console.log( last );

			}
*/
			console.log( ret );


			return properties;
		},



		/**
		 * Save a media column 
		 * 
		 * @param  {[type]} properties [description]
		 * @return {[type]}            [description]
		 */
		saveProperties: function( properties ){

			var self = this;
			self.$( '.loader' ).addClass( 'show' );

			var data = {
						'action' 		: 'saveColumnProperties',
						'column_id'		: self.columnId,
						'post_id'		: self.postId,
						'section_id'	: self.sectionId,
						'full_id'		: self.fullId,
						'type' 			: self.$( '.column-controls .type-select' ).val(),
						'properties'	: properties
			};



			jQuery.post( ajaxurl, data, function( response ){

				//console.log( 'response: '+response );
				//console.log( self.$el );
				//error handeling
				self.closeLightbox();

				self.$el.replaceWith( response );
				setColumns();
				setSections();

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

			self.$( '.loader' ).addClass( 'show' );

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
				setSections();
			});

		}

	});


	jQuery( document ).ready( function( $ ){


		setColumns();

	});


	var _cols = [];

	function setColumns(){

		_cols = [];

		jQuery('.column' ).each( function( index, obj ){
			var col = new Column( { el: obj } );
			_cols.push( col );
		});
	}