


	var Section = Backbone.View.extend({

		hasLightbox: true,

		sectionId: '',
		postId: '',

		events: {

			'change .section-controls .type-radio': 'changeView',
			'click .delete-section': 'deleteSection',
			'click .code-snitch': 'copyCode',
			'click .section-settings-btn' : 'toggleSettings'

		},

		/**
		 * Init the section object
		 * 
		 * @return void
		 */
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

		/**
		 * Show the loader
		 * 
		 * @param  jQuery $
		 * @return void
		 */
		showLoader: function( $ ){

			var self = this;
			self.$( '> .loader' ).addClass( 'show' );
		},

		/**
		 * Show the settings field
		 * 
		 * @param  Event evt
		 * @return void
		 */
		toggleSettings: function( evt ){

			var self = this;

			self.$el.find('.section-settings').toggleClass( 'active' );

		},

		/**
		 * Code copy event function
		 * 
		 * @param  Event evt
		 * @return void
		 */
		copyCode: function( evt ){

			var self = this;
			var string = self.$el.find( '.copy' ).html();
			self.copyToClipboard( string );

		},

		/**
		 * Copy the contents to your clipboard
		 * 
		 * @param  string _value
		 * @return void
		 */
		copyToClipboard: function( _value ){
			jQuery( 'body' ).append("<input type='text' id='temp' style='position:absolute;opacity:0;'>");
			jQuery( '#temp' ).val( _value ).select();
			document.execCommand( 'copy' );
			jQuery( '#temp' ).remove();
		},

		/**
		 * Remove section-events
		 * 
		 * @return void
		 */
		destroy: function(){
			this.undelegateEvents();
		}


	});





	jQuery( document ).ready( function( $ ){

		setSections();

		/**
		 * Adding sections
		 * @return html
		 */
		$('#addSection').on( 'click', function(){

			var data = {
				action: 'createSection',
				post_id: $( this ).data('post_id')
			}

			$('#section-builder-ui .spinner').addClass( 'show' );

			$.post( ajaxurl, data, function( response ){

				$('#section-container').append( response );

				//register new section here:
				
				setSections();
				
				//set new columns with parent:
				setColumns();

				//refresh the fields
				refreshFields();

				//remove the spinner:
				$('#section-builder-ui .spinner').removeClass( 'show' );
				
			});
		});


		/**
		 * Fetching a section-template
		 * @return html
		 */
		$('#getTemplate').on( 'change', function(){


			if( $( this ).val() !== 'none' ){

				var data = {
					action: 'loadTemplate',
					post_id: $( this ).data( 'post_id' ),
					template_id: $( this ).val()
				}
	
				$('.section-wrapper.msg').addClass('loading');
	
				$.post( ajaxurl, data, function( response ){
	
	
					jQuery('#section-container').append( response );
					$('.section-wrapper.msg').remove();
	
					setSections();
					setColumns();
					refreshFields();
				
				});
			}
		});

		/**
		 * Changing the template type:
		 * 
		 * @param  Event e
		 * @return void
		 */
		$('.type-select').on( 'change', function( e ){

			if( $( this ).hasClass( 'field-apply_to') == false ){

				if( $( e.target ).val() === 'blueprint' ){
	
					$('.field-apply_to').parent().removeClass( 'not-visible' );
			
				}else{
			
					$('.field-apply_to').parent().addClass( 'not-visible' );
			
				}
			}
		});

		/**
		 * Sort sections
		 * 
		 * @type void
		 */
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
		});
	});



	var _sections = [];

	function setSections(){

		if( _sections.length > 1 ){

			for( var i = 0; _sections.length > i; i++ ){
				_sections[ i ].destroy();
			}
		}

		_sections = [];

		jQuery('.section-wrapper').each( function( index, obj ){

			var sec = new Section( { el: obj } );
			_sections.push( sec );

		});

		setSectionOrder();
	}


	function setSectionOrder(){

		var i = 1;
		jQuery('#section-container .section-wrapper').each( function(){
			var field = jQuery( this ).find( '.section-position' );
			field.val( i );
			i++;
		})
	}
