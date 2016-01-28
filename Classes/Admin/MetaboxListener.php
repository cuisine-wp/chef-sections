<?php

	namespace ChefSections\Admin;

	use \Cuisine\Wrappers\Metabox;
	use \Cuisine\Wrappers\Field;
	use \Cuisine\Wrappers\PostType;
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

			$fields = $this->getFields();

			$name = __( 'Instellingen', 'chefsections');
			Metabox::make( $name, 'section-template', array( 'context' => 'side' ) )->set( $fields );
		}

		/**
		 * Gets the fields for our metabox
		 * 
		 * @return array
		 */
		private function getFields(){

			$postTypes = $this->getPostTypes();

			//return the fields:
			return array(

				Field::checkbox(

					'show_in_admin',
					'Maak selecteerbaar',
					array(
						'defaultValue' => true
					)

				),

				Field::select(

					'apply_to',
					'Stel standaard in op',
					$postTypes,
					array(
						'defaultValue'	=> 'none'
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
				'none'	=> __( 'Geen specifieke bericht types', 'chefsections' )
			);

			foreach( $pts as $type ){

				$postTypes[ $type ] = PostType::name( $type );

			}

			return $postTypes;
		}
		


	}

	if( is_admin() )
		\ChefSections\Admin\MetaboxListeners::getInstance();

