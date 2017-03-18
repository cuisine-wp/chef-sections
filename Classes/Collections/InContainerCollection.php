<?php

	namespace ChefSections\Collections;


	class InContainerCollection extends Collection{


		/**
		 * Container section ID
		 * 
		 * @var int
		 */
		protected $sectionId;


		/**
		 * Name of the meta-key for these items
		 * 
		 * @var string
		 */
		protected $metaName = 'sections';


		/**
		 * Constructor
		 * 
		 * @param int $postId
		 * @param int $sectionId
		 *
		 * @return void
		 */
		public function __construct( $postId, $sectionId )
		{
			$this->sectionId = $sectionId;
			parent::__construct( $postId );
		}


		/**
		 * Returns the items of this collection
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			$sections = parent::getItems();
			$items = [];

			foreach( $sections as $key => $section ){



				if( isset( $section['container_id'] ) && $section['container_id'] == $this->sectionId )
					$items[ $key ] = $section;

			}

			return $items;
		}
	}