<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\StaticInstance;
	use ChefSections\Wrappers\SectionsUi;
	use ChefSections\Admin\Ui\Toolbar;
	use ChefSections\Walkers\SectionCollection;
	use ChefSections\Sections\Manager as SectionManager;
	use ChefSections\Admin\Generators\Blueprint;

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

					Toolbar::build();
					SectionsUi::build();
				
				}

			});

			//saving
			add_action( 'save_post', function( $post_id ){

				( new SectionManager( $post_id ) )->saveSections();

			});


			/**
			 * WP SEO changes:
			 */

			//remove the metabox from our templates:
			add_action( 'add_meta_boxes', function(){

				remove_meta_box( 'wpseo_meta', 'section-template', 'normal' );

			}, 20 );

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

					( new Blueprint( $post_id ) )->maybeGenerate();

				}
			});
		}

	}

	if( is_admin() )
		\ChefSections\Admin\EventListeners::getInstance();
