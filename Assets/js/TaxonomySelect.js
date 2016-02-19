jQuery( document ).ready( function( $ ){

 	var TaxonomySelectField = Backbone.View.extend({
	
		taxonomies: {},
		id: '',
		highestId: '',
		container: '',


		events: {

		},
 	
 		initialize: function(){

 			var self = this;
 			console.log( 'wop' );
 			//self.id = self.$el.data('id');
 			//self.highestId = parseInt( self.$el.data( 'highest-id' ) );
 			/*
 			self.container = self.$el.find( '.media-inner' );
 			self.setItems();
 			self.setItemEvents();
 			self.setItemPositions();*/

 			self.setChosen();

 			self.taxonomies = JSON.parse( Taxonomies );
 			console.log( self.taxonomies );

 		},


 		/**
 		 * Set chosen for the term-field:
 		 *
 		 * @return void
 		 */
 		setChosen: function(){

 			self.$el.find('.term-selector').each( function(){

 				$( this ).chosen({
 					disable_search: true,
 				});

 			});
 		}

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
