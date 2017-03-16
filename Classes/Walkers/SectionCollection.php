<?php

	namespace ChefSections\Walkers;

	use Cuisine\Utilities\Sort;
	use ChefSections\Sections\Section;
	use ChefSections\Sections\Blueprint;
	use ChefSections\Sections\Reference;
	use ChefSections\Sections\Stencil;
	use ChefSections\Sections\Container;

	class SectionCollection{


		/**
		 * Post ID for this collection
		 * 
		 * @var int
		 */
		protected $postId;


		/**
		 * All sections in an array
		 * 
		 * @var Array
		 */
		protected $sections;


		/**
		 * All sections in an array
		 * 
		 * @var Array
		 */
		protected $array;


		/**
		 * Highest ID
		 * 
		 * @var integer
		 */
		protected $highestId;



		/**
		 * Constructor
		 * 
		 * @param int $postId
		 */
		public function __construct( $postId )
		{
			$this->postId = $postId;
			$this->sections = $this->getCollection();
			$this->highestId = null;
		}



		/*=============================================================*/
		/**             Getters & Setters                              */
		/*=============================================================*/

		/**
		 * Returns all sections
		 * 
		 * @return array
		 */
		public function get()
		{
			return $this->sections;
		}


		/**
		 * Return all sections as a multidimensional array
		 * 
		 * @return array
		 */
		public function toArray()
		{
			return $this->array;
		}



		/**
		 * Get all non-containered sections
		 * 
		 * @return array
		 */
		public function getNonContainered()
		{
			$_result = [];
			foreach( $this->sections as $section ){

				if( is_null( $section->container_id ) )
					$_result[] = $section;

			}

			return $_result;
		}



		/**
		 * Fetches the info from the database and populates it with section-objects
		 * 
		 * @return array
		 */
		public function getCollection(){

			$sections = get_post_meta( $this->postId, 'sections', true );
			$this->array = $sections;

			$array = array();

			if( is_array( $sections ) && !empty( $sections ) ){
			
				$sections = Sort::byField( $sections, 'position', 'ASC' );
			
				if( $sections ){

					foreach( $sections as $section ){
						
						$array[ $section['id'] ] = $this->getSectionType( $section );
					
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

					return new Section( $section );

				break;


			}
		}



		/*=============================================================*/
		/**             Section ID increments                          */
		/*=============================================================*/


		/**
		 * Returns the highest ID of this collection
		 * 
		 * @return int
		 */
		public function getHighestId()
		{

			if( !is_null( $this->highestId ) )
				return $this->highestId;


			$highID = 0;

			if( !empty( $this->sections ) ){

				foreach( $this->sections as $section ){

					if( $section->id > $highID )
						$highID = $section->id;

				}

			}

			$this->highestId = $highID;
			return $highID;
		}


		/**
		 * Returns the highest ID of this collection
		 * 
		 * @return int
		 */
		public function setHighestId( $amount = 1 )
		{
			if( is_null( $this->highestId ) )
				$this->getHighestId();

			$this->highestId = $this->highestId + $amount;

			return $this->highestId;
		}


	}