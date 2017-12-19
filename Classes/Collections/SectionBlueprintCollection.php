<?php

	namespace CuisineSections\Collections;

	use WP_Query;
	use Cuisine\Utilities\Sort;
	use CuisineSections\SectionTypes\Reference;

	class SectionBlueprintCollection extends Collection{

		/**
		 * Constructor
		 * 
		 * @param int $postId
		 */
		public function __construct()
		{
			$this->objects = [];
			$this->items = $this->getItems();
			$this->returnValue = 'objects';
		}



		/**
		 * Returns the items of this collection
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			$items = [];

			$args = $this->getSectionBlueprintQuery();
			$blueprints = new WP_Query( $args );

			if( $blueprints->have_posts() ){

				//convert all WP_Post objects to arrays:
				foreach( $blueprints->posts as $item ){
					
					$this->objects[ $item->ID ] = $item;
					$items[ $item->ID ] = $item->to_array();
				}
			}

			return $items;
		}


	

		/**
		 * Returns all arguments for a reference query
		 * 
		 * @return array
		 */
		public function getSectionBlueprintQuery()
		{

			$args = array( 
				'post_type' => 'section-template', 
				'posts_per_page' => -1
			);

			$args = apply_filters( 'cuisine_sections_section_blueprint_collection_query', $args, $this );
			return $args;
		}

	}