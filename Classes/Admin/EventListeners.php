<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\StaticInstance;
	use ChefSections\Wrappers\SectionsBuilder;
	use Cuisine\Utilities\Url;

	class EventListeners extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		public function __construct(){

			$this->listen();

		}

		/**
		 * All admin actions
		 * 
		 * @return void
		 */
		private function listen(){


			add_action( 'admin_init', function(){

				//remove editors from the post-types:
				$post_types = array( 'page' );
				$include = apply_filters( 'chef_sections_remove_editor', $post_types );
				$post_types = apply_filters( 'chef_sections_post_types', $post_types );

				foreach( $post_types as $type ){

					if( in_array( $type, $include ) )
						remove_post_type_support( $type ,'editor' );
				
				}				

			});


			add_action( 'edit_form_after_editor', function(){

				global $post;
				
				if( isset( $post ) )
					SectionsBuilder::build();
				

			});


			add_action( 'save_post', function( $post_id ){

				SectionsBuilder::save( $post_id );

			});



			//filter for WP SEO:
			add_filter( 'wpseo_pre_analysis_post_content', function( $content ){

				if( has_sections() )
					$content = get_sections();

				return $content;
			
			});

		}

	}

	if( is_admin() )
		\ChefSections\Admin\EventListeners::getInstance();
