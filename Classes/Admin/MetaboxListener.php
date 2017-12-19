<?php

	namespace CuisineSections\Admin;

	use \Cuisine\Wrappers\Metabox;
	use \Cuisine\Wrappers\Field;
	use \Cuisine\Wrappers\PostType;
	use \Cuisine\Utilities\Session;
	use \CuisineSections\Wrappers\StaticInstance;

	class MetaboxListeners extends StaticInstance{


		/**
		 * Init admin metaboxes
		 */
		function __construct(){

			$this->metaboxes();

		}


		/**
		 * Creates the metaboxes for this plugin
		 * 
		 * @return void
		 */
		private function metaboxes(){

			add_action( 'admin_init', function(){

				$name = __( 'Settings', 'CuisineSections');

				//section templates:
				Metabox::make( 
			
					$name, 
					'section-template', 
					array( 'context' => 'side' )
			
				)->set([

					Field::checkbox(
						'editable',
						__( 'Is this section template editable?', 'CuisineSections' )
					)
				
				]);


				//page templates:
				Metabox::make( 
			
					$name, 
					'page-template', 
					array( 'context' => 'side' )
			
				)->set([
					Field::select(
						'apply_to',
						__( 'Apply as default to', 'CuisineSections' ),
						$this->getPostTypes(),
						array(
							'defaultValue'	=> 'none',
						)
					)
				]);
			
			});
		}




		/**
		 * Get the post types available for selecting:
		 * 
		 * @return array
		 */
		private function getPostTypes(){
			
			$pts = array( 'page' );
			$pts = apply_filters( 'chef_sections_post_types', $pts );


			$postTypes = array(
				'none'	=> __( 'No specific post types', 'CuisineSections' )
			);

			foreach( $pts as $type ){

				$postTypes[ $type ] = PostType::name( $type );

			}

			return $postTypes;
		}
		


	}
