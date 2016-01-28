<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\StaticInstance;
	use ChefSections\Wrappers\SectionsBuilder;
	use ChefSections\Wrappers\StencilBuilder;
	use Cuisine\Utilities\Url;

	class EventListeners extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		public function __construct(){

			$this->listen();

			$this->templates();

		}

		/**
		 * All admin actions
		 * 
		 * @return void
		 */
		private function listen(){

			//remove editor support
			add_action( 'admin_init', function(){

				//remove editors from the post-types:
				$post_types = array( 'page', 'section-template' );
				$include = apply_filters( 'chef_sections_remove_editor', $post_types );
				$post_types = apply_filters( 'chef_sections_post_types', $post_types );

				foreach( $post_types as $type ){

					if( in_array( $type, $include ) )
						remove_post_type_support( $type ,'editor' );
				
				}				

			});

			//add roles
			add_action( 'init', function(){

				//set the edit_sections capability to the administrator role
				$role = get_role( 'administrator' );
				$role->add_cap( 'edit_sections' );

			});


			//placing the sections builder
			add_action( 'edit_form_after_editor', function(){

				global $post;

				//allow sections to be turned off on a per-post basis.
				$dontload = apply_filters( 'chef_sections_dont_load', array() );
				
				if( isset( $post ) && !in_array( $post->ID, $dontload ) ){

					SectionsBuilder::build();
				
				}

			});

			//saving
			add_action( 'save_post', function( $post_id ){

				SectionsBuilder::save( $post_id );

			});


			/**
			 * WP SEO changes:
			 */

			//remove the metabox from our templates:
			add_action( 'add_meta_boxes', function(){

				remove_meta_box( 'wpseo_meta', 'section-template', 'normal' );

			}, 20 );



			//filter for WP SEO content-check
			/* add_filter( 'wpseo_pre_analysis_post_content', function( $content ){

				if( has_sections() )
					$content = get_sections();

				return $content;
			
			});*/

		}


		/**
		 * Listen to the section-template events:
		 * 
		 * @return void
		 */
		private function templates(){


			//when creating a new post, check if we need to apply a template:
			add_action( 'save_post', function( $post_id ){
				
				global $pagenow, $post;
				$status = get_post_status( $post_id );


				if( $status == 'auto-draft' && $pagenow == 'post-new.php' ){

					StencilBuilder::applyTemplates( $post_id );

				}
			});



		}

	}

	if( is_admin() )
		\ChefSections\Admin\EventListeners::getInstance();
