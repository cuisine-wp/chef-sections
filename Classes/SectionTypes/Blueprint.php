<?php

	namespace ChefSections\SectionTypes;

	use ChefSections\Wrappers\Column;
	use Cuisine\Wrappers\User;

	/**
	 * Blueprints are full-scale section-templates tied to post-types
	 */
	class Blueprint extends BaseSection{


		/**
		 * Section-type "Blueprint".
		 * 
		 * @var string
		 */
		public $type = 'blueprint';



		/**
		 * The post-type for this blueprint
		 * 
		 * @var string
		 */
		public $postType = 'page';


		/**
		 * Returns all public attributes
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$attributes = parent::getAttributes();
			$blueprintAttributes = [
				'postType'
			];

			return array_merge( $attributes, $blueprintAttributes );
		}


		/**
		 * Add the postType argument
		 * 
		 * @param  Array $args
		 * 
		 * @return Array
		 */
		public function sanitizeArgs( $args )
		{
			$args = parent::sanitizeArgs( $args );

			if( !isset( $args['postType'] ) )
				$args['postType'] = 'page';

			return $args;
		}

	}