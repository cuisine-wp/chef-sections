<?php

	namespace ChefSections\Front;

	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Scripts;

	class Assets{

		/**
		 * Assets instance.
		 *
		 * @var \ChefSections\Front
		 */
		private static $instance = null;


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->enqueues();

		}

		/**
		 * Init the Assets classes
		 *
		 * @return \ChefSections\Front\Assets
		 */
		public static function getInstance(){

		    if ( is_null( static::$instance ) ){
		        static::$instance = new static();
		    }
		    return static::$instance;
		}


		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){

			add_action( 'init', function(){

				$url = Url::plugin( 'chef-sections', true ).'Assets/js/';
				$libs = $url.'libs/';
	
				Scripts::register( 'isotope', $libs.'isotope.min.js', false );
				Scripts::register( 'imagesloaded', $libs.'imagesloaded.min.js', false );
				Scripts::register( 'autoload', $libs.'autoload.js', false );
			
			});
		}



	}

	if( !is_admin() )
		\ChefSections\Front\Assets::getInstance();
