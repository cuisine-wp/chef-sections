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


			//include the media js in section templates:
			add_action( 'admin_init', function(){
				
				global $pagenow;

				if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ){
					if( 
						( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'section-template' ) || 
						( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'section-template' )
					){

						wp_enqueue_media();
					
					}
				}

			});

			
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

