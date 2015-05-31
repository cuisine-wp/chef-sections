/*!
 * Autoload v1.1.0
 *
 * Licensed GPLv3
 *
 * http://www.chefduweb.nl/plugins/autoload
 * Copyright 2015 Chef du Web
 */
;(function ( $, window, document, undefined ) {

		// Create the defaults once
		var pluginName = "AutoLoad";
		var defaults = {
				loaderClass: 'autoload-loader',
				pageNumber: 1,
				postType: 'post',
				template: 'row',
				postsPerPage: 12,
				message: 'Geen berichten gevonden',
				wrap: false,
				beforeLoad: null,
				afterLoad: null,
				afterPlacement: null,
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

						if( self.doneLoading === false ){
							
							var scrollPos = $(window).scrollTop();
							scrollPos += $(window).height();
							
							if( scrollPos >= self.triggerY && self.updating === false ){
							
								self.updating = true;
								self.autoload();
	
							}
						}
					});

				},


				autoload: function(){
				
					var self = this;
					self.settings.pageNumber++;

					var data = {
						action: 'chef_autoload',
						page_number: this.settings.pageNumber,
						post_type: this.settings.postType,
						template: this.settings.template,
						posts_per_page: this.settings.PostsPerPage,
						message: this.settings.message,
						wrap: this.settings.wrap
					};

					if( self.settings.beforeLoad !== null )
						self.settings.beforeLoad( self, data );
				
					//post with ajax:
					$.post( ajaxurl, data, function(response) {

						//Handle a 'no more posts' message:
						if( response === 0 || response === 'message' ){

							self.doneLoading = true;
							self.reset();

							var msg = self.settings.message;
							if( self.settings.wrap ){
								msg = '<p class="autoload-msg message">'+msg+'</p>';
							}

							$( self.element ).append( msg );

						}else{

							if( self.settings.afterLoad !== null )
								self.settings.afterLoad( self, response );

							$( '.'+self.settings.loaderClass ).remove();
							$( self.element ).append( response );

							if( self.settings.afterPlacemnet !== null )
								self.settings.afterPlacement( self, response );

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
						html += '</div>';

						$( this.element ).append( html );

						this.calculateTrigger();
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