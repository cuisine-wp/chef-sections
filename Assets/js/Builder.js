
var SectionBuilder = new function(){

	var _columns;
	var _sections;
	var _postId;
	var _htmlOutput;

	/****************************************/
	/***	Public callable functions
	/****************************************/

	/**
	 * Initting this builder:
	 * 
	 * @return void
	 */
	this.init = function(){

		var self = this;

		self._columns = new Array();
		self._sections = new Array();
		self._htmlOutput = '';
		self._postId = ChefSections.postId;


		//yoast support:
		if( typeof( YoastSEO ) != 'undefined' )
			self.initYoastSupport();

		//events:
		self.setEvents();

		//column and section arrays:
		self.setBuilder();
		self.setColumns();
		self.setSections();

	}

	/**
	 * Refresh columns and sections
	 * 
	 * @return void
	 */
	this.refresh = function(){

		var self = this;

		self.setColumns();
		self.setSections();

		//update the eventual output:
		self.updateHtmlOutput();

	}

	/**
	 * Events for the builder
	 *
	 * @return void
	 */
	this.setEvents = function(){

		var self = this;

		self.setSectionTypeSelect();
		self.setSectionTemplateSelector();
		self.setAddSectionButton();
		self.setSectionsSortable();

	}

	this.setBuilder = function(){

		var _w = $('.section-container').innerWidth() + 30;
		$( '#section-builder-ui' ).css({
			width: _w+'px'
		});
	}
	

	/****************************************/
	/***	Refreshers
	/****************************************/


	/**
	 * Set all columns
	 *
	 * @return void
	 */
	this.setColumns = function(){

		var self = this;

		if( self._columns.length > 0 ){

			for( var i = 0; self._columns.length > i; i++ ){
				self._columns[ i ].destroy();

			}

		}


		self._columns = [];

		jQuery('.column' ).each( function( index, obj ){
			var col = new Column( { el: obj } );
			self._columns.push( col );
		});
	}

	/**
	 * Set all sections
	 *
	 * @return void
	 */
	this.setSections = function(){

		var self = this;

		if( self._sections.length > 1 ){

			for( var i = 0; self._sections.length > i; i++ ){
				self._sections[ i ].destroy();
			}
		}

		self._sections = [];

		jQuery('.section-wrapper').each( function( index, obj ){

			var sec = new Section( { el: obj } );
			self._sections.push( sec );

		});

		self.setSectionOrder();


	}


	/****************************************/
	/***	Events
	/****************************************/

	/**
	 * Making sections sortable
	 *
	 * @returns void
	 */
	this.setSectionsSortable = function(){

		var self = this;
		$('#section-container').sortable({
			handle: '.pin',
			placeholder: 'section-placeholder',
			update: function (event, ui) {
				self.setSectionOrder();
			}
		});

	}

	/**
	 * Set section order
	 *
	 * @return void
	 */
	this.setSectionOrder = function(){

		var i = 1;
		jQuery('#section-container .section-wrapper').each( function(){
			var field = jQuery( this ).find( '.section-position' );
			field.val( i );
			i++;
		})
	}


	/**
	 * Changing the template type:
	 * 
	 * @param  Event e
	 * @return void
	 */
	this.setSectionTypeSelect = function(){

		var self = this;

		$('.type-select').on( 'change', function( e ){

			if( $( this ).hasClass( 'field-apply_to') == false ){

				if( $( e.target ).val() === 'blueprint' ){
		
					$('.field-apply_to').parent().removeClass( 'not-visible' );
			
				}else{
			
					$('.field-apply_to').parent().addClass( 'not-visible' );
			
				}
			}
		});
	}

	/**
	 * Fetching a section-template
	 * 
	 * @return html
	 */
	this.setSectionTemplateSelector = function(){

		var self = this;

		$('#getTemplate').on( 'change', function(){

			if( $( '#getTemplate' ).val() !== 'none' ){

				var data = {
					action: 'loadTemplate',
					post_id: $( '#getTemplate' ).data( 'post_id' ),
					template_id: $( '#getTemplate' ).val()
				}
		
				$('.section-wrapper.msg').addClass('loading');
		
				$.post( ajaxurl, data, function( response ){
		
		
					jQuery('#section-container').append( response );
					$('.section-wrapper.msg').remove();
		
					self.refresh();
					refreshFields();
				
				});
			}
		});
	}

	/**
	 * Adding sections 
	 * 
	 * @return html
	 */
	this.setAddSectionButton = function(){

		var self = this;

		//add on click:
		$('#addSection').on( 'click', function(){

			var data = {
				action: 'createSection',
				post_id: $( this ).data('post_id')
			}

			$('#section-builder-ui .spinner').addClass( 'show' );

			$.post( ajaxurl, data, function( response ){

				$('#section-container').append( response );

				//register new section here:
				self.refresh();

				//refresh the fields
				refreshFields();

				//remove the spinner:
				$('#section-builder-ui .spinner').removeClass( 'show' );
				
			});
		});


		$('#addSection').draggable({
			connectToSortable: '#section-container',
			helper: 'clone',
			stop: function( event, ui ){
				
				var _placeholder = $('#section-container .section-btn.ui-draggable-handle' );
				_placeholder.addClass('placeholder-block');
				_placeholder.html( '<span class="spinner"></span> Adding section...' );

				//_placeholder.replaceWith('pants!');
				var data = {
					action: 'createSection',
					post_id: _placeholder.data('post_id')
				}

				$.post( ajaxurl, data, function( response ){

					_placeholder.replaceWith( response );

					//order items:
					self.setSectionOrder();

					//register new section here:
					self.refresh();

					//refresh the fields
					refreshFields();

				});
			}
		});
	}

	/****************************************/
	/***	Yoast Functions
	/****************************************/

	/**
	 * Start the yoast support
	 * 
	 * @return void
	 */
	this.initYoastSupport = function(){

		var self = this;

		YoastSEO.app.registerPlugin( 'chefSections', {status: 'loading'} );
		YoastSEO.app.pluginReady( 'chefSections' );

		//register the content modification:
		YoastSEO.app.registerModification( 'content', function( _data ){

			if( self._htmlOutput !== '' )
				return self._htmlOutput;

			return _data;

		}, 'chefSections', 5 );


		self.updateHtmlOutput();
	}

	/**
	 * Update the HTML output for Yoast
	 * 
	 * @return void
	 */
	this.updateHtmlOutput = function(){

		var self = this;
		
		var data = {
			action: 'getHtmlOutput',
			post_id: self._postId
		}


		$.post( ajaxurl, data, function( response ){

			self._htmlOutput = response;

			//reload the plugin:
			if( typeof( YoastSEO ) != 'undefined' )
				YoastSEO.app.pluginReloaded( 'chefSections' );


		});
	}



}	


//init sections builder
jQuery( window ).load( function( $ ){

	SectionBuilder.init();
	
});
