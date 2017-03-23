
	var Container = Backbone.View.extend({

		hasLightbox: true,

		sectionId: '',
		postId: '',

		events: {

			'click .container-footer .delete-section': 'deleteSection',
			'click > .section-controls .buttons-wrapper .button' : 'toggleSettingPanel',
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

							self.$el.slideUp( 'fast', function(){
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

			var _panel = self.$el.find('> .section-setting-panels #panel-'+_id);

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
		 * Remove section-events
		 * 
		 * @return void
		 */
		destroy: function(){
			this.undelegateEvents();
		}


	});

