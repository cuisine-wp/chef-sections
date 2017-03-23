<?php

	namespace ChefSections\Collections;

	use Cuisine\Utilities\Sort;
	use ChefSections\Helpers\Section as SectionHelper;

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


		/**
		 * Switches the raw html data to objects
		 * 
		 * @return array
		 */
		public function createObjects(){

			$array = array();

			if( !$this->empty() ){
			
				$sections = Sort::byField( $this->items, 'position', 'ASC' );
			
				if( $sections ){

					foreach( $sections as $section ){

						if( $section['type'] != 'container' )
							$array[ $section['id'] ] = SectionHelper::getClass( $section );
					
					}
				}
			}

			return $array;
		}



		/**
		 * Returns the correct Section class
		 * 
		 * @return Section / Reference / Layout / Stencil
		 */
		public function getSectionType( $section ){

			if( !isset( $section['type'] ) )
				$section['type'] = 'section';
			
			switch( $section['type'] ){

				case 'reference':

					return new Reference( $section );

				break;
				case 'blueprint':

					return new Blueprint( $section );

				break;
				case 'stencil':

					return new Stencil( $section );

				break;
				default:

					return new ContentSection( $section );

				break;


			}
		}


	}