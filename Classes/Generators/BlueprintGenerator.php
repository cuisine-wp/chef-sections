<?php

	namespace ChefSections\Admin\Generators;

	use WP_Query;
	use ChefSections\SectionTypes\Blueprint as BlueprintSection;
	use ChefSections\Contracts\Generator as GeneratorContract;

	class Blueprint extends BaseGenerator implements GeneratorContract{



		/**
		 * Set the data straight
		 * 
		 * @param  Array  $data
		 * 
		 * @return Array
		 */
		public function sanitizeData( Array $data ){

			if( sizeof( $data['sections'] ) > 0 ){
				
				//set all sections to arrays,
				//and all column data to arrays
				foreach( $data['sections '] as $key => $section ){

					if( !is_array( $section ) )
						$data['sections'][ $key ] = $section->toArray();

					if( sizeof( $section['columns'] ) > 0 ){
						
						foreach( $section['columns'] as $colKey => $column ){
							
							$data['sections'][ $key ]['columns'][ $colKey ] = $column->toArray();

						}
					}
				}
			}

			return $data;
		}

	
		/**
		 * Load sections from a template
		 * 
		 * @return bool
		 */
		public function generate( $templateId = null ){
			
			// create blueprint here
			
		}


	}