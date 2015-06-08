<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\StaticInstance;
	use Cuisine\Utilities\Url;

	class Assets extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		public function __construct(){
			
			$this->enqueues();

		}

		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){

			
			add_action( 'admin_menu', function(){

				$url = Url::plugin( 'chef-sections', true ).'Assets';
				wp_enqueue_script( 
					'sections_section', 
					$url.'/js/Section.js', 
					array( 'backbone', 'media-editor' )
				);

				wp_enqueue_script( 
					'sections_column', 
					$url.'/js/Column.js', 
					array( 'backbone', 'media-editor' ) 
				);

				
				wp_enqueue_style( 'sections', $url.'/css/admin.css' );
				
			});
			
		}



	}
	
	if( is_admin() )
		\ChefSections\Admin\Assets::getInstance();

