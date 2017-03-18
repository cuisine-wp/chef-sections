<?php

	namespace ChefSections\SectionTypes;

	use ChefSections\Collections\InContainerCollection;

	class Container extends BaseSection{

		/**
		 * Strint to detirmen which type of section this is
		 *
		 * @possible values: section - reference - stencil - container
		 * 
		 * @var string
		 */
		public $type = 'container';


		/**
		 * The sections array
		 * 
		 * @var Array
		 */
		public $sections;


		/**
		 * Returns all public attributes
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$attributes = parent::getAttributes();
			$containerAttributes = [
				'sections'
			];

			return array_merge( $attributes, $containerAttributes );
		}


		/**
		 * Add the template_id and Parent to our arguments
		 * 
		 * @param  Array $args
		 * 
		 * @return Array
		 */
		public function sanitizeArgs( $args )
		{
			$args = parent::sanitizeArgs( $args );

			if( !isset( $args['sections'] ) )
				$args['sections'] = new InContainerCollection( $args['post_id'], $args['id'] );

			return $args;
		}



		/*=============================================================*/
		/**             Getters & Setters                              */
		/*=============================================================*/


		/**
		 * Return an empty array, since we have no columns in this container:
		 * 
		 * @return array
		 */
		public function getColumns( $columns )
		{
			return [];
		}


		/**
		 * Returns a collection of sections in this container
		 * 
		 * @return ChefSections\Collections\InContainerCollection;
		 */
		public function getSections(){
			return $this->sections;
		}

	}