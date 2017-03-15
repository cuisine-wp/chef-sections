$ = jQuery;

var TaxonomySelectField = Backbone.View.extend({

taxonomies: {},
pickedTaxonomies: {},
highestId: '',


	initialize: function(){

		var self = this;
		self.highestId = parseInt( self.$el.data( 'highest-id' ) );
		
		self.setChosen();
		self.setEvents();

		self.taxonomies = JSON.parse( Taxonomies );

	},


	/**
	 * Set the events for this field:
	 *
	 * @return void
	 */
	setEvents: function(){

		var self = this;
	
		self.$el.on( 'click tap', '.add-remove-btn', function(){


			if( $( this ).hasClass( 'add-tax' ) ){

				if( $('.tax-select-wrapper' ).length <= 0 )
					$('.taxonomy-select-field').html('');

				var _newId = self.getHighestId();

				var htmlTemplate = _.template( jQuery( '#taxonomy_select_item').html() );
				var output = htmlTemplate({
				    iteration: _newId,
				});

				self.$el.append( output );
				self.setChosen( _newId );



			}else{

				var _tax = $( this ).parent().parent();

				if( confirm( 'Are you sure you want to delete this?' ) ){


					_tax.fadeOut( 'fast', function(){
						_tax.remove();
					
						//add a fallback, if there are no filters:
						if( $('.tax-select-wrapper' ).length <= 0 ){

							var _html = '<p>At this moment ther are no extra filters active.</p>';
							_html += '<span class="add-remove-btn add-tax msg-add-remove">Create a filter</span>';
							self.$el.append( _html );

						}
					});

				}

			}

		});


		self.$el.on( 'change', '.taxonomy-selector', function(){

			var _tax = $( this ).val();
			var _termSelector = $( this ).siblings( '.term-selector' );

			var _terms = self.taxonomies[ _tax ];
			var _html = '';

			for( var i = 0; i < _terms.length; i++ ){

				var _term = _terms[ i ];
				_html += '<option value="'+_term.slug+'">'+_term.name+'</option>';

			}

			_termSelector.html( _html );
			_termSelector.trigger("chosen:updated");

		});

	},

	/**
	 * Set chosen for the term-field:
	 *
	 * @return void
	 */
	setChosen: function(){

		var self = this;

		self.$el.find('.term-selector').each( function(){

			$( this ).chosen({
				disable_search: true,
			});

		});
	},

	/**
	 * Update chosen:
	 * 
	 * @return void
	 */
	updateChosen: function( _newId ){
		
		var self = this;

		self.$el.find('#tax-'+_newId+' .term-selector').chosen({
			disable_search: true
		});
		
	},

	/**
	 * Gets the highest ID and upps it by one
	 * 
	 * @return void:
	 */
	getHighestId: function(){

		var self = this;

		self.highestId = parseInt( self.highestId ) + 1;
		return self.highestId;

	},


	destroy : function() {
		this.undelegateEvents();
	}


});

	


	

var _taxSelect = [];

jQuery( document ).ready( function( $ ){
	chefSectionsSetTaxonomySelect();
});

jQuery( document ).on( 'refreshFields', function(){
	chefSectionsSetTaxonomySelect();
});

function chefSectionsSetTaxonomySelect(){

	if( _taxSelect.length > 0 ){
		for( var i = 0; _taxSelect.length > i; i++ ){
			_taxSelect[ i ].destroy();
		}
	}

	_taxSelect = [];

 	jQuery('.taxonomy-select-field' ).each( function( index, obj ){
 		var ts = new TaxonomySelectField( { el: obj } );
 		_taxSelect.push( ts );
 	});
	
}

