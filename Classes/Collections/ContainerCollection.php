<?php

	namespace ChefSections\Collections;

	use Cuisine\Utilities\Session;

	class ContainerCollection extends Collection{


		/**
		 * Constructor
		 * 
		 * @param int $postId
		 */
		public function __construct()
		{
			$this->objects = [];
			$this->items = $this->getItems();
			$this->returnValue = 'array';
		}



		/**
		 * Returns the items of this collection
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			$items = apply_filters( 'chef_sections_containers', [] );
			$this->objects = $items;
			return $items;
		}


		/**
		 * Returns the container by ID
		 * 
		 * @return array
		 */
		public function getById( $containerId, $postId = null )
		{
			if( is_null( $postId ) )
				$postId = Session::postId();

			$sections = get_post_meta( $postId, 'sections', true );
			$container = $sections[ $containerId ];
			$slug = $container['slug'];

			$item = $this->get( $slug );
			$item['slug'] = $slug;

			return $item;
		}


	}