<?php

	namespace ChefSections\Front;

	use ChefSections\Wrappers\StaticInstance;
	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Scripts;
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
				
				Scripts::register( 'isotope', $url.'isotope.min', false );
				Scripts::register( 'imagesloaded', $url.'imagesloaded.min', false );
				Scripts::register( 'autoload', $url.'autoload', false );

				//sass:
				$url = 'chef-sections/Assets/sass/front/';
				
				Sass::register( 'columns', $url.'_columns.scss', false );
				Sass::register( 'collection', $url.'_collection.scss', false );
				Sass::register( 'loader', $url.'_loader.scss', false );
				
			});
		}



	}

	if( !is_admin() )
		\ChefSections\Front\Assets::getInstance();
