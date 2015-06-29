<?php

	namespace ChefSections\Front;

	use ChefSections\Wrappers\StaticInstance;
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
				$url = Url::plugin( 'chef-sections', true ).'Assets/js/libs/';
				
				Script::register( 'isotope', $url.'isotope.min', false );
				Script::register( 'imagesloaded', $url.'imagesloaded.min', false );
				Script::register( 'autoload', $url.'autoload', false );

				//sass:
				$url = 'chef-sections/Assets/sass/front/';
				
				Sass::register( 'sections-columns', $url.'_columns', false );
				Sass::register( 'sections-collection', $url.'_collection', false );
				Sass::register( 'sections-loader', $url.'_loader', false );
				Sass::register( 'sections-socials', $url.'_socials', false );
				Sass::register( 'sections-responsive', $url.'_responsive', false );
				
			});
		}
	}

	\ChefSections\Front\Assets::getInstance();
