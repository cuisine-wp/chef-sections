<?php

	namespace ChefSections\Collections;

	use Cuisine\Utilities\Sort;
	use ChefSections\Helpers\Section as SectionHelper;

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
						$instance = SectionHelper::getClass( $section );
                        if( !is_null( $instance ) ){
						    $array[ $section['id'] ] = $instance;
                        }
					}
				}
			}

			return $array;
		}

	}
