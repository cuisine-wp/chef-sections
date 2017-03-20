
	var Section = Backbone.View.extend({

		hasLightbox: true,

		sectionId: '',
		postId: '',

		events: {

			'change .section-controls .type-radio': 'changeView',
			'click .delete-section': 'deleteSection',
			'click .code-snitch': 'copyCode',
			'click .section-controls .buttons-wrapper .button' : 'toggleSettingPanel',
			'click #close-panel' : 'hideSettingPanel',
			'change .title-radio .multi' : 'setHeaderType'

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

			self.setEvents();
		},

		/**
		 * Make columns sortable:
		 *
		 * @return void
		 */
		setEvents: function(){

			var self = this;

			self.$('.section-columns').sortable({
				handle: '.sort',
				tolerance: 'pointer',
				placeholder: 'placeholder-column',
				update: function (event, ui) {
					
					//self.setSectionOrder();
					var positions = new Array();
					var i = 1;

					self.$el.find('.section-columns .column').each(function(){
						positions.push( $( this ).data('column_id') );
						$( this ).find( '.column-position' ).val( i );
						i++;
					});


					var data = {
						action: 'sortColumns',
						post_id: self.$el.data( 'post_id' ),
						column_ids: positions,
						section_id: self.sectionId
					}


					$.post( ajaxurl, data, function( response ){
						
						//console.log( response );
					
					});
				}
			});

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

				try{

					response = JSON.parse( response );
					
					//console.log( response );				
					self.$el.replaceWith( response.html );

					SectionBuilder.refresh();
					refreshFields();
					

				}catch( e ){
					
					console.log( e );

				}
				console.log( response );
				

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
				
				self.$el.addClass('deleting');

				jQuery.post( ajaxurl, data, function( response ){
										
					try{

						response = JSON.parse( response );
						if( response.error == false || response.error == 'fase' ){

							self.$el.slideUp( 'slow', function(){

								if( typeof( self.$el.data('tab') ) != 'undefined' ){

									//set first tab as active:
									self.$el.parent().parent().find('.section-sortables > .tab').first().addClass( 'active' );
									
									//remove old tab:
									$( '#'+ self.$el.data('tab') ).remove();
								}

								self.$el.remove();
							});
		
							SectionBuilder.refresh();
							refreshFields();
						}

					} catch( e ){

						console.log( e );

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
		 * Show a settings panel
		 * 
		 * @param  Event evt
		 * @return void
		 */
		toggleSettingPanel: function( evt ){

			var self = this;
			var _id = $( evt.target ).data('id');
			
			var _offset = $( evt.target ).position().left;
			_offset = parseInt( _offset ) + 27;

			var _panel = self.$el.find('#panel-'+_id);

			if( _panel.hasClass('active' ) === false ){

				$('.section-setting-panels > div' ).removeClass( 'active' );

				_panel.addClass( 'active' );
				_panel.find('.arrow').css({
					left: _offset+'px'
				})

			}else{
				_panel.removeClass( 'active' );
			}


		},

		/**
		 * Show a settings panel
		 * 
		 * @param  Event evt
		 * @return void
		 */
		hideSettingPanel: function( evt ){

			$('.section-setting-panels > div' ).removeClass( 'active' );

		},

		/**
		 * Set the header tag type
		 * 
		 * @param Event evt
		 * @return void
		 */
		setHeaderType: function( evt ){

			var _item = $( evt.target );
			var _val = _item.val();

			_item.parent().parent().parent().find( '.icon' ).html( _val );

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

