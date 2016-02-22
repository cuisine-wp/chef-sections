jQuery( document ).ready( function( $ ){

 	var TaxonomySelectField = Backbone.View.extend({
	
		taxonomies: {},
		highestId: '',

 	
 		initialize: function(){

 			var self = this;
 			self.highestId = parseInt( self.$el.data( 'highest-id' ) );
 			
 			self.setChosen();
 			self.setEvents();

 			self.taxonomies = JSON.parse( Taxonomies );
 			console.log( self.taxonomies );

 		},


 		/**
 		 * Set the events for this field:
 		 *
 		 * @return void
 		 */
 		setEvents: function(){

 			var self = this;
 		
 			self.$el.on( 'click tap', '.add-remove-btn', function(){

 				var _id = $( this ).data( 'id' );

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

 					if( confirm( 'Weet u zeker dat u dit wil verwijderen?' ) ){

 						$('#tax-'+_id).fadeOut( 'fast', function(){
 							$('#tax-'+_id).remove();
 						
 							//add a fallback, if there are no filters:
 							if( $('.tax-select-wrapper' ).length <= 0 ){

 								var _html = '<p>Op dit moment zijn er geen extra filters actief.</p>';
 								_html += '<span class="add-remove-btn add-tax msg-add-remove">Maak een filter aan</span>';
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

	




	chefSectionsSetTaxonomySelect();



	function chefSectionsSetTaxonomySelect(){

	 	jQuery('.taxonomy-select-field' ).each( function( index, obj ){
	 		console.log( 'test' );
	 		var ts = new TaxonomySelectField( { el: obj } );
	 		//_mediaFields.push( mf );
	 	});
		
	}

});
