


	var Section = Backbone.View.extend({

		hasLightbox: true,

		sectionId: '',
		postId: '',

		events: {

			'change .section-controls .type-radio': 'changeView',
			'click .delete-section': 'deleteSection',
			'click .code-snitch': 'copyCode',

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
				refreshFields();

			});
		},


		/**
		 * Delete a section
		 * 
		 * @return void
		 */
		deleteSection: function(){

			var self = this;

			self.initialize();
			var data = {

				action: 'deleteSection',
				section_id: self.sectionId,
				post_id: self.postId,

			}

			if( confirm( "Weet je zeker dat je deze sectie wil verwijderen?" ) ){
				jQuery.post( ajaxurl, data, function( response ){
					
					if( response === 'true' ){
	
						self.$el.slideUp( 'slow', function(){
							self.$el.remove();
						});
	
						setSections();
						setColumns();
						refreshFields();
					}
	
				});
			}
		},


		showLoader: function( $ ){

			var self = this;
			self.$( '> .loader' ).addClass( 'show' );
		},

		copyCode: function( evt ){

			var self = this;
			var string = self.$el.find( '.copy' ).html();
			self.copyToClipboard( string );

		},


		copyToClipboard: function( _value ){
			console.log( _value )
			jQuery( 'body' ).append("<input type='text' id='temp' style='position:absolute;opacity:0;'>");
			jQuery( '#temp' ).val( _value ).select();
			document.execCommand( 'copy' );
			jQuery( '#temp' ).remove();
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
				refreshFields();
				
			});

		});

		var _post_id = $( '.section-wrapper' ).first().data('post_id');
		$('#section-container').sortable({
			handle: '.pin',
			placeholder: 'section-placeholder',
			update: function (event, ui) {

				var positions = $( "#section-container" ).sortable( "toArray" );
				
				var data = {
					action: 'sortSections',
					post_id: _post_id,
					section_ids: positions
				}

				$.post( ajaxurl, data, function( response ){
				
					var i = 0;
					jQuery( '.section-wrapper').each( function(){

						var field = jQuery( this ).find( '.section-position' );
						var _val = field.val();
						field.val( i );
						i++;
						
					});

				});

			}
		})

	});



	function setSections(){

		jQuery('.section-wrapper').each( function( index, obj ){

			var sec = new Section( { el: obj } );

		});
	}
