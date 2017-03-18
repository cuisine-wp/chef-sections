<?php

	namespace ChefSections\SectionTypes;

	use Cuisine\Wrappers\User;
	use Cuisine\Wrappers\Field;
	use Cuisine\Utilities\Sort;
	use ChefSections\Wrappers\Column;
	use ChefSections\Collections\SectionCollection;

	/**
	 * References are meant for use in 'regular' section-flows.
	 */
	class Reference extends BaseSection{

		/**
		 * Section-type "Reference".
		 * 
		 * @var string
		 */
		public $type = 'reference';


		/**
		 * The ID for the template, if this is a template-based section
		 * 
		 * @var integer
		 */
		public $template_id = 0;


		/**
		 * Parent for this reference
		 * 
		 * @var ChefSections\SectionTypes\BaseSection;
		 */
		public $parent;



		/**
		 * Returns all public attributes
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$attributes = parent::getAttributes();
			$referenceAttributes = [
				'template_id',
				'parent'
			];

			return array_merge( $attributes, $referenceAttributes );
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

			if( isset( $args['template_id'] ) && $args['template_id'] != $args['post_id'] ){

				if( !isset( $args['parent'] ) )
					$args['parent'] = ( new SectionCollection( $args['template_id'] ) )->first();
			}

			return $args;
		}



		/**
		 * Get the Columns in an array
		 * 
		 * @return array
		 */
		public function getColumns( $columns ){

			//if we're in reference-mode, fetch the columns of the parent:
			if( !is_null( $this->parent ) )
				return $this->parent->columns;

			//otherwise, just get the columns for this section:
			return parent::getColumns( $columns );

		}

	}
