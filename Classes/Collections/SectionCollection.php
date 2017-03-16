<?php

	namespace ChefSections\Collections;

	use Cuisine\Utilities\Sort;
	use ChefSections\Sections\Section;
	use ChefSections\Sections\Blueprint;
	use ChefSections\Sections\Reference;
	use ChefSections\Sections\Stencil;
	use ChefSections\Sections\Container;

	class SectionCollection extends Collection{


		/**
		 * Name of the meta-key for these items
		 * 
		 * @var string
		 */
		protected $metaName = 'sections';



		/**
		 * Get all non-containered sections
		 * 
		 * @return array
		 */
		public function getNonContainered()
		{
			$_result = [];
			foreach( $this->objects as $section ){

				if( is_null( $section->container_id ) )
					$_result[] = $section;

			}

			return $_result;
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
	}