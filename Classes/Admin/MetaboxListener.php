<?php

	namespace ChefSections\Admin;

	use \Cuisine\Wrappers\Metabox;
	use \Cuisine\Wrappers\Field;
	use \Cuisine\Wrappers\PostType;
	use \Cuisine\Utilities\Session;
	use \ChefSections\Wrappers\StaticInstance;

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

				$fields = $this->getFields();
				$name = __( 'Settings', 'chefsections');

				Metabox::make( 
			
					$name, 
					'section-template', 
					array( 'context' => 'side' )
			
				)->set( $fields );
			
			});
		}



		/**
		 * Gets the fields for our metabox
		 * 
		 * @return array
		 */
		private function getFields(){

			$postTypes = $this->getPostTypes();

			$templateTypes = array(
				'reference'	=> __( 'Reference', 'chefsections' ),
				'blueprint' => __( 'Blueprint', 'chefsections' ),
				'stencil'	=> __( 'Stencil', 'chefsections' )
			);

			$templateTypes = apply_filters( 'chef_sections_template_types', $templateTypes );

			$currentType = get_post_meta( Session::postId(), 'type', true );
			$class = ( $currentType == 'blueprint' ? array( 'active' ) : array( 'not-visible' ) );

		

			//return the fields:
			return array(

				Field::select(

					'type',
					__( 'Template type', 'chefsections' ),
					$templateTypes,
					array(
						'defaultValue' => 'reference'
					)

				),

				Field::select(

					'apply_to',
					__( 'Apply as default to', 'chefsections' ),
					$postTypes,
					array(
						'defaultValue'	=> 'none',
						'wrapper-class'	=> $class
					)
				)

			);

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
				'none'	=> __( 'No specific post types', 'chefsections' )
			);

			foreach( $pts as $type ){

				$postTypes[ $type ] = PostType::name( $type );

			}

			return $postTypes;
		}
		


	}

	if( is_admin() )
		\ChefSections\Admin\MetaboxListeners::getInstance();

