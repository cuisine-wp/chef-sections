/*!
 * Autoload v1.1.0
 *
 * Licensed GPLv3
 *
 * http://www.chefduweb.nl/plugins/autoload
 * Copyright 2015 Chef du Web
 */
 define([
 	
 	'jquery',
 	'autoload',
 	'imagesloaded',
 	'isotope',

], function( $ ){

	;(function ( $, window, document, undefined ) {
	
			// Create the defaults once
			var pluginName = "AutoLoad";
			var defaults = {
					loaderClass: 'autoload-loader',
					loaderContent: '',
					pageNumber: 1,
					column: '',
					section: '',
					message: 'Geen berichten gevonden',
					wrap: true,
					postId: 0,
					onTrigger: null,
					onLoaded: null,
					onComplete: null,
					onAllComplete: null
			};
	
			// The actual plugin constructor
			function Plugin ( element, options ) {
	
					this.element = element;
					this.triggerY = 9999;
					this.updating = false;
					this.doneLoading = false;
					// jQuery has an extend method which merges the contents of two or
					// more objects, storing the result in the first object. The first object
					// is generally empty as we don't want to alter the default options for
					// future instances of the plugin
					this.settings = $.extend( {}, defaults, options );
					this._defaults = defaults;
					this._name = pluginName;
					this.init();
			}
	
			// Avoid Plugin.prototype conflicts
			$.extend(Plugin.prototype, {
					
	
					init: function () {
	
						this.setupLoader();
						this.setupLoadEvent();
	
					},
	
	
					setupLoadEvent: function(){
	
						var self = this;
	
						$(window).scroll( function( e ){
	
							if( self.doneLoading === false && $( self.element ).hasClass( 'hold-autoload') === false ){
								
								self.calculateTrigger();

								var scrollPos = $(window).scrollTop();
								scrollPos += $(window).height();
								
								if( scrollPos >= self.triggerY && self.updating === false ){
									self.updating = true;
									self.updateLoader();
									self.autoload();
		
								}
							}
						});
	
					},
	
	
					autoload: function(){
					
						var self = this;
						self.settings.pageNumber++;
	
						var data = {
							action: 'autoload',
							page: this.settings.pageNumber,
							section: this.settings.section,
							column: this.settings.column,
							post_id: this.settings.postId,
							message: this.settings.message,
							wrap: this.settings.wrap
						};
	
						if( self.settings.onTrigger !== null )
							self.settings.onTrigger( self, data );
					
						//post with ajax:
						$.post( Cuisine.ajax, data, function(response) {
	
							//Handle a 'no more posts' message:
							if( response === 0 || response === 'message' ){
	
								self.doneLoading = true;
								self.reset();
	
								var msg = self.settings.message;
								if( self.settings.wrap ){
									msg = '<p class="autoload-msg message">'+msg+'</p>';
								}
	
								$( self.element ).append( msg );
	
								if( self.settings.onAllComplete !== null )
									self.settings.onAllComplete( self, response );
	
							}else{
	
								if( self.settings.onLoaded !== null )
									self.settings.onLoaded( self, response );
	
								$( '.'+self.settings.loaderClass ).remove();
								$( self.element ).append( response );
	
								if( self.settings.onComplete !== null )
									self.settings.onComplete( self, response );
	
								self.reset();
							}
	
			
						})
	
	
					},
	
	
					reset: function(){
	
						this.updating = false;
						this.setupLoader();
	
					},
	
	
					setupLoader: function(){
	
						$( '.'+this.settings.loaderClass ).remove();
	
						if( this.doneLoading === false ){
	
							var html = ''
							html += '<div class="'+this.settings.loaderClass+'">';
							html += this.settings.loaderContent;
							html += '</div>';
	
							$( this.element ).append( html );
	
							if( $( this.element ).hasClass( 'masonry' ) ){
	
								$( '.'+this.settings.loaderClass ).css({
									'position': 'absolute',
									'bottom': '-30px',
									'left': '40%'
								});
							}
	
	
							this.calculateTrigger();
						}
	
					},
	
					updateLoader: function(){
	
						if( this.updating ){
							$('.'+this.settings.loaderClass).addClass( 'visible' );
						}else{
							$('.'+this.settings.loaderClass).removeClass( 'visible');
						}
					},
	
					calculateTrigger: function(){
	
						var _y = $( '.'+this.settings.loaderClass ).offset().top;
						this.triggerY = _y;
					}
	
	
					
			});
	
			// A really lightweight plugin wrapper around the constructor,
			// preventing against multiple instantiations
			$.fn[ pluginName ] = function ( options ) {
					this.each(function() {
							if ( !$.data( this, "plugin_" + pluginName ) ) {
									$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
							}
					});
	
					// chain jQuery functions
					return this;
			};
	
	})( jQuery, window, document );
});