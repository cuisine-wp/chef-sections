<?php

	namespace CuisineSections\Admin;

	use Cuisine\Utilities\Url;
	use CuisineSections\Helpers\PostType;
	use CuisineSections\Admin\Ui\Toolbar;
	use CuisineSections\Admin\Ui\SectionsUi;
	use CuisineSections\Wrappers\StaticInstance;
	use CuisineSections\Admin\Handlers\SectionHandler;
	use CuisineSections\Admin\Handlers\ContainerHandler;
	use CuisineSections\Admin\Handlers\PageBlueprintHandler;

	use CuisineSections\Helpers\Section as SectionHelper;

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
				$post_types = array( 'page', 'section-template', 'page-template' );
				$include = apply_filters( 'chef_sections_remove_editor', $post_types );
				$post_types = apply_filters( 'chef_sections_post_types', $post_types );

				foreach( $post_types as $type ){

					if( in_array( $type, $include ) )
						remove_post_type_support( $type ,'editor' );
				
				}			

			});


			/*add_action( 'admin_footer', function(){

				$_POST['post_id'] = '198';
				$_POST['section_id'] = '8';

				( new SectionHandler() )->addSection();

			});*/

			//add roles
			add_action( 'init', function(){

				//set the edit_sections capability to the administrator role
				$role = get_role( 'administrator' );
				if( !is_null( $role ) )
					$role->add_cap( 'edit_sections' );

			});

			


			//placing the sections builder
			add_action( 'edit_form_after_editor', function(){

				global $post;

				if( PostType::isValid( $post->ID ) ){

					//allow sections to be turned off on a per-post basis.
					$dontload = apply_filters( 'chef_sections_dont_load', array() );
				
					if( isset( $post ) && !in_array( $post->ID, $dontload ) ){

						( new Toolbar() )->build();
						( new SectionsUi() )->build();
				
					}
				}

			});

			//saving
			add_action( 'save_post', function( $post_id ){

				( new SectionHandler( $post_id ) )->saveSections();

			});


			/**
			 * WP SEO changes:
			 */

			//remove the metabox from our templates:
			add_action( 'add_meta_boxes', function(){

				remove_meta_box( 'wpseo_meta', 'section-template', 'normal' );
				remove_meta_box( 'wpseo_meta', 'page-template', 'normal' );

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
				
				( new PageBlueprintHandler( $post_id ) )->maybeGenerate();

			});
		}

	}
