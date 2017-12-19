<?php

	namespace CuisineSections\SectionTypes;

	use CuisineSections\Collections\InContainerCollection;

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
		 * Container slug
		 * 
		 * @var string
		 */
		public $slug;


		/**
		 * Returns all public attributes
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$attributes = parent::getAttributes();
			$containerAttributes = [
				'sections',
				'slug'
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

			//hard-set the view for each container:
			$args['view'] = $this->getView();

			if( !isset( $args['sections'] ) )
				$args['sections'] = new InContainerCollection( $args['post_id'], $args['id'] );

			//fall back on the only container we know for sure we've got.
			if( !isset( $args['slug'] ) )
				$args['slug'] = 'group';

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
		 * @return CuisineSections\Collections\InContainerCollection;
		 */
		public function getSections(){
			return $this->sections;
		}

		/**
		 * Returns the view for this container
		 * 
		 * @return string
		 */
		public function getView()
		{
			return 'grouped';
		}

	}