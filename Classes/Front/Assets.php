<?php

	namespace CuisineSections\Front;

	use CuisineSections\Wrappers\StaticInstance;
	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Script;
	use Cuisine\Wrappers\Sass;


	class Assets extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->enqueues();

		}


		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){

			add_action( 'init', function(){

				//scripts:
				$url = Url::plugin( 'cuisine-sections', true ).'Assets/js/libs/';
				
				Script::register( 'isotope', $url.'isotope.min', false );
				Script::register( 'imagesloaded', $url.'imagesloaded.min', false );
				Script::register( 'autoload', $url.'autoload', false );
				Script::register( 'fitvids', $url.'fitvids.min', false );

				//sass:
				if( !Sass::ignore() ){
					
					$url = 'cuisine-sections/Assets/sass/front/';
					
					Sass::register( 'sections-columns', $url.'_columns', false );
					Sass::register( 'sections-collection', $url.'_collection', false );
					Sass::register( 'sections-loader', $url.'_loader', false );
					Sass::register( 'sections-socials', $url.'_socials', false );
					Sass::register( 'sections-responsive', $url.'_responsive', false );

				}else{

					//we need to ignore sass and enqueue a regular css file:
					add_action( 'wp_enqueue_scripts', function(){

						wp_enqueue_style( 'chef_sections', Url::plugin( 'cuisine-sections', true ).'Assets/css/compiled.css' );

					});

				}
			});
		}
	}

	\CuisineSections\Front\Assets::getInstance();
