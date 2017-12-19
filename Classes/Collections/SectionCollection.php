<?php

	namespace CuisineSections\Collections;

	use Cuisine\Utilities\Sort;
	use CuisineSections\Helpers\Section as SectionHelper;

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
			foreach( $this->objects as $key => $section ){

				if( is_null( $section->container_id ) )
					$_result[ $key ] = $section;

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

			if( !$this->isEmpty() ){
			
				$sections = Sort::byField( $this->items, 'position', 'ASC' );
			
				if( $sections ){

					foreach( $sections as $section ){
						
						$array[ $section['id'] ] = SectionHelper::getClass( $section );
					
					}
				}
			}

			return $array;
		}

	}