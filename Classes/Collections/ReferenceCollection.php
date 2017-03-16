<?php

	namespace ChefSections\Collections;

	use WP_Query;
	use Cuisine\Utilities\Sort;
	use ChefSections\SectionTypes\Reference;

	class ReferenceCollection extends Collection{

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

			$args = $this->getReferenceQuery();
			$references = new WP_Query( $args );

			if( $references->have_posts() ){

				//convert all WP_Post objects to arrays:
				foreach( $references->posts as $item ){
					
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
		public function getReferenceQuery()
		{

			$args = array( 
				'post_type' => 'section-template', 
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'	=> 'type',
						'value' => 'reference'
					)
				)
			);

			$args = apply_filters( 'chef_sections_reference_collection_query', $args, $this );
			return $args;
		}

	}