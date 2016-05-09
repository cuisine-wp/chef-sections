
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

				SectionBuilder.refresh();
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
	
						SectionBuilder.refresh();
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

