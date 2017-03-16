<?php

	namespace ChefSections\Collections;


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



	}